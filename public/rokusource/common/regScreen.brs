'********************************************************************
'** display the registration screen in its initial state with the
'** text "retreiving..." shown.  We'll get the code and replace it
'** in the next step after we have something onscreen for teh user 
'********************************************************************
Function displayRegistrationScreen(settings As Object) As Object

    regsite   = "go to " + settings.RokuActivation
    regscreen = CreateObject("roCodeRegistrationScreen")
    regscreen.SetMessagePort(CreateObject("roMessagePort"))

    regscreen.SetTitle("")
    regscreen.AddParagraph("Please link your Roku player to your account by visiting")
    regscreen.AddFocalText(" ", "spacing-dense")
    regscreen.AddFocalText("From your computer,", "spacing-dense")
    regscreen.AddFocalText(regsite, "spacing-dense")
    regscreen.AddFocalText("and enter this code to activate:", "spacing-dense")
    regscreen.SetRegistrationCode("retrieving code...")
    regscreen.AddParagraph("This screen will automatically update as soon as your activation completes")
    regscreen.AddButton(0, "Get a new code")
    regscreen.AddButton(1, "Cancel")
    regscreen.Show()

    return regscreen

End Function


'********************************************************************
'** Fetch the prelink code from the registration service. return
'** valid registration code on success or an empty string on failure
'********************************************************************
Function getRegistrationCode(sn As String, settings As Object) As String

    if sn = "" then return ""

    'http = NewHttp(settings.APIUrl + "/getActivationCode")
    http = NewHttp(settings.APIUrl + "?action=getActivationCode")
    rsp = http.Http.GetToString()
    return rsp

    xml = CreateObject("roXMLElement")
    print "GOT: " + rsp
    print "Reason: " + http.Http.GetFailureReason()

    if not xml.Parse(rsp) then
        print "Can't parse getRegistrationCode response"
        ShowConnectionFailed()
        return ""
    endif

    if xml.GetName() <> "result"
        Dbg("Bad register response: ",  xml.GetName())
        ShowConnectionFailed()
        return ""
    endif

    if islist(xml.GetBody()) = false then
        Dbg("No registration information available")
        ShowConnectionFailed()
        return ""
    endif

    'default values for retry logic
    retryInterval = 30  'seconds
    retryDuration = 900 'seconds (aka 15 minutes)
    regCode       = ""

    'handle validation of response fields 
    for each e in xml.GetBody()
        if e.GetName() = "regCode" then
            regCode = e.GetBody()  'enter this code at website
        elseif e.GetName() = "retryInterval" then
            retryInterval = strtoi(e.GetBody())
        elseif e.GetName() = "retryDuration" then
            retryDuration = strtoi(e.GetBody())
        endif
    next

    if regCode = "" then
        Dbg("Parse yields empty registration code")
        ShowConnectionFailed()
    endif

    m.retryDuration = retryDuration
    m.retryInterval = retryInterval
    m.regCode = regCode

    return regCode

End Function


'***************************************************************
' The retryInterval is used to control how often we retry and
' check for registration success. its generally sent by the
' service and if this hasn't been done, we just return defaults 
'***************************************************************
Function getRetryInterval() As Integer
    if m.retryInterval < 1 then m.retryInterval = 30
    return m.retryInterval
End Function


'**************************************************************
' The retryDuration is used to control how long we attempt to 
' retry. this value is generally obtained from the service
' if this hasn't yet been done, we just return the defaults 
'**************************************************************
Function getRetryDuration() As Integer
    if m.retryDuration < 1 then m.retryDuration = 900
    return m.retryDuration
End Function


'******************************************************
'Load/Save RegistrationToken to registry
'******************************************************

Function loadRegistrationToken() As dynamic
    m.RegToken =  RegRead("RegToken", "Authentication")
    if m.RegToken = invalid then m.RegToken = ""
    return m.RegToken 
End Function

Sub saveRegistrationToken(token As String, regCode as String)
    RegWrite("RegToken", token, "Authentication")
    RegWrite("RegCode", regCode, "Code")
End Sub

Sub deleteRegistrationToken()
    RegDelete("RegToken", "Authentication")
    m.RegToken = ""
End Sub

Function isLinked() As Dynamic
   
    if Len(m.RegToken) > 0  then return true
    return false
End Function

Function isLinkedBox(sn As String, settings As Object) As Integer
    
    'http = NewHttp(settings.APIUrl + "/isRegistered?deviceid=" + sn)
    http = NewHttp(settings.APIUrl + "?action=isRegistered?d=" + sn)
    rsp = http.Http.GetToString()
    print rsp

    scRecList = strTokenize(rsp, "|")
    if (scRecList[0]="1")
        return val(scRecList[2])
    endif
    
    return 0

    'http.AddParam("regCode", rc)
    'print "checking activation status..."
    while true

            xml = CreateObject("roXMLElement")
            if not xml.Parse(rsp) then
               print "Can't parse check activation status response"
               ShowConnectionFailed()
               return 0
            endif
            
            if xml.GetName() <> "result" then
                 print "unexpected check activation status response: ", xml.GetName()
                 ShowConnectionFailed()
                 return 0
            endif
            
            if islist(xml.GetBody()) = true then
                    for each e in xml.GetBody()
                        if e.GetName() = "status" then
                            token = e.GetBody()
                            if token <> "" and token <> invalid  then
                                print "obtained activation status token: " + validstr(token)
                                return 1
                            else
                                print "Invalid token"
                                print m.UrlGetRegStatus
                                return 0
                            endif
                        elseif e.GetName() = "regCode" then
                            sn = GetDeviceESN() 
                            regCode = validstr(e.GetBody())
                            saveRegistrationToken(sn, regCode) 
                        elseif e.GetName() = "customerId" then
                            customerId = strtoi(e.GetBody())
                        elseif e.GetName() = "creationTime" then
                            creationTime = strtoi(e.GetBody())
                        endif
                    next
            endif
     end while      

    print "Activation Status: " + validstr(regToken) +  " for " + validstr(customerId) + " at " + validstr(creationTime) 
    return 0 
       
End Function

'******************************************************
'Show congratulations screen
'******************************************************
Function showCongratulationsScreen(mainScreen)
    port = CreateObject("roMessagePort")
    screen = CreateObject("roParagraphScreen")
    screen.SetMessagePort(port)

    screen.AddHeaderText("Congratulations!")
    screen.AddParagraph("You have successfully linked your Roku player to your account")
    screen.AddParagraph("Select 'start' to begin.")
    screen.AddButton(1, "start")
    screen.Show()

    while true
        msg = wait(0, screen.GetMessagePort())

        if type(msg) = "roParagraphScreenEvent"
            if msg.isScreenClosed()
                print "Screen closed"
                exit while                
            else if msg.isButtonPressed()
                print "Button pressed: "; msg.GetIndex(); " " msg.GetData()
                screen.Close()
                return 0
				'showHomeScreen(mainScreen)
                exit while
            else
                print "Unknown event: "; msg.GetType(); " msg: "; msg.GetMessage()
                exit while
            endif
        endif
    end while
    return 1
End Function

