Function CenterAlignX(gds As Object, width As Integer)  As Integer
    screen_center% = gds.w / 2
    screen_center% = screen_center% - (width / 2)
    return screen_center%
End Function

Function X(gds As Object, xPos As Double)  As Integer
    screen_center# = gds.w * xPos / 100.0
    screen_center% = screen_center#
    return screen_center%
End Function

Function Y(gds As Object, yPos As Double)  As Integer
    screen_center# = gds.h * yPos / 100.0
    screen_center% = screen_center#
    return screen_center%
End Function

Function GetUserInput(normalText As Boolean, caption$ As String, defaultData$ As String) As String
    screen = CreateObject("roKeyboardScreen")
    port = CreateObject("roMessagePort")
    screen.SetMessagePort(port)
    screen.SetTitle("")
    screen.SetText(defaultData$)
    screen.SetDisplayText(caption$)
    screen.setSecureText(normalText)
    screen.AddButton(1, "finished")
    screen.AddButton(2, "back")
    screen.Show()

    while true
        msg = wait(0, screen.GetMessagePort())
        print "message received"
        if (type(msg) = "roKeyboardScreenEvent") Then
            if (msg.isScreenClosed()) Then
                return defaultData$
            else if (msg.isButtonPressed()) Then
                'print "Evt: msg.GetMessage();" idx:"; msg.GetIndex()
                if (msg.GetIndex() = 1) then 
                    return screen.GetText()
                else if (msg.GetIndex() = 2) then 
                    return defaultData$
                endif
            endif
        endif
    end while
End Function

Function preShowParagraphScreen(breadA=invalid, breadB=invalid) As Object
    port=CreateObject("roMessagePort")
    screen = CreateObject("roParagraphScreen")
    screen.SetMessagePort(port)
    return screen
End Function

Function ShowLoginScreen(settings As Object) As Integer
    regStatus = 0
    if (settings.loginmode="yesR") then
        regStatus = doRegistration2(settings)
    else if (settings.loginmode="yesW") then
        regStatus = doRegistration(settings)
    end if

    if (regStatus = 1) then
        ' Registration successful
        return 1
    endif
    return 0
End Function

Function showInfoScreen(item as object, settings As Object) As Integer
    port = CreateObject("roMessagePort")
    screen = CreateObject("roSpringboardScreen")

    'print "showSpringboardScreen"

    screen.SetMessagePort(port)
    screen.AllowUpdates(false)
    if item <> invalid and type(item) = "roAssociativeArray"
        screen.SetContent(item)
    endif

    screen.SetDescriptionStyle("generic") 'audio, movie, video, generic' generic+episode=4x3,
    screen.ClearButtons()
    'screen.AddButton(1,"Free Access")
    screen.AddButton(2,"Activate Your Roku Box")
    screen.AddButton(5,"Sign Up")

    screen.SetStaticRatingEnabled(false)
    screen.AllowUpdates(true)
    screen.Show()

    downKey=3
    selectKey=6
    while true
        msg = wait(0, port)
        'print msg
        if type(msg) = "roSpringboardScreenEvent" then
        
            'print "Button pressed: "; msg.GetIndex(); " " msg.GetData()

            if (msg.isButtonPressed()) then
                if msg.GetIndex() = 2
                    if (ShowLoginScreen(settings)=1) return 1
                else if msg.GetIndex() = 5
                    'print "displaySignupScreen"
                    displaySignupScreen(settings)

                else if msg.GetIndex() = 9
                    'print "return back"
                    return -1   

                else if msg.GetIndex() = 10
                    'print "return back"
                    return -1   
                endif
            else if msg.isScreenClosed() then
                'print "return back"
                return -1   
            endif
        endif
    end while
    return -1
End Function

Function showInfo(settings As Object) As Integer
    if (settings.login="no") return 1

    rnds = Rnd(10)
    itemVenter = { ContentType:"episode"
               SDPosterUrl:settings.channelLogo
               HDPosterUrl:settings.channelLogo 
               IsHD:False
               HDBranded:False
               ShortDescriptionLine1:""
               ShortDescriptionLine2:""
               Description: ""
               Length:1972
               Categories:["English"]
               Title:"Please select an option below:"
               }
    return showInfoScreen(itemVenter, settings)  
End Function


Function displaySignupScreen(settings As Object) As Boolean
    screen=preShowParagraphScreen("Paragraph", "")
    screen.SetTitle(settings.channelName)
    screen.AddHeaderText("Signup Now!")
    screen.AddParagraph(settings.signupText)
    screen.AddParagraph("Signup Link: " + settings.RokuActivation)  
    screen.AddButton(1,"Close")
    screen.Show()

    while true
        msg = wait(0, screen.GetMessagePort())
        if type(msg) = "roParagraphScreenEvent"
            if msg.isScreenClosed()
                'print "Screen closed"
                exit while                
            else if msg.isButtonPressed()
                'print "Button pressed: "; msg.GetIndex(); " " msg.GetData()
                return 0
                'exit while
            else
                'print "Unknown event: "; msg.GetType(); " msg: "; msg.GetMessage()
                exit while
            endif
        endif
    end while

End Function

'--------------------------------------------------------------------------------------------------------
Function ShowLoginStatusDialog(message As String) As Void
    port = CreateObject("roMessagePort")
    dialog = CreateObject("roMessageDialog")
    dialog.SetMessagePort(port)
    dialog.SetTitle("Login Status")
    dialog.SetText(message)
    dialog.AddButton(1, "OK")
    dialog.Show()
    while true
        dlgMsg = wait(0, dialog.GetMessagePort())
        if (type(dlgMsg) = "roMessageDialogEvent") then
            if (dlgMsg.isScreenClosed()) then
                return
            else if (dlgMsg.isButtonPressed()) then
                'print "Button pressed: "; dlgMsg.GetIndex(); " " dlgMsg.GetData()
                return
            end if
        end if
    end while
End Function

Function doRegistration2Screen(gds As Object, scRecList As Object, userName$ As String, password$ As String, index As Integer) As Object
    pwd$ = ""
    for i = 1 to len(password$)
        pwd$ = pwd$ + "*"
    next

    pUX! = val(scRecList[11])
    pUY! = val(scRecList[12])
    pPX! = val(scRecList[13])
    pPY! = val(scRecList[14])

    if (index = 1) then
        loginForm  = [  {   url:scRecList[16] 
                            TargetRect:{x:X(gds, pUX!),y:Y(gds,pUY!),w:300,h:50}
                            CompositionMode:"Source_Over"
                        },
                        {   url:scRecList[15] 
                            TargetRect:{x:X(gds, pPX!),y:Y(gds,pPY!),w:300,h:50}
                            CompositionMode:"Source_Over"
                        },
                        {   Text:userName$ 
                            TextAttrs:{Color:"#FF000000", Font:"Small", HAlign:"Left", VAlign:"VCenter", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, pUX!+2),y:Y(gds,pUY!),w:250, h:50}
                            CompositionMode:"Source"
                        },
                        {   Text:pwd$ 
                            TextAttrs:{Color:"#FF000000", Font:"Small", HAlign:"Left", VAlign:"VCenter", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, pPX!+2),y:Y(gds,pPY!),w:250, h:50}
                            CompositionMode:"Source"
                        },
                        {    Text:"Sign In" 
                            TextAttrs:{Color:"#FFff0000", Font:"Small", HAlign:"Center", VAlign:"Center", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, 45), y:Y(gds,65), w:X(gds, 10), h:Y(gds, 10)}
                            CompositionMode:"Source"
                        },
        ]
        return loginForm
    else if (index = 2) then
        loginForm  = [  {   url:scRecList[15] 
                            TargetRect:{x:X(gds, pUX!),y:Y(gds,pUY!),w:300,h:50}
                            CompositionMode:"Source_Over"
                        },
                        {   url:scRecList[16] 
                            TargetRect:{x:X(gds, pPX!),y:Y(gds,pPY!),w:300,h:50}
                            CompositionMode:"Source_Over"
                        },
                        {   Text:userName$ 
                            TextAttrs:{Color:"#FF000000", Font:"Small", HAlign:"Left", VAlign:"VCenter", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, pUX!+2),y:Y(gds,pUY!),w:250, h:50}
                            CompositionMode:"Source"
                        },
                        {   Text:pwd$ 
                            TextAttrs:{Color:"#FF000000", Font:"Small", HAlign:"Left", VAlign:"VCenter", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, pPX!+2),y:Y(gds,pPY!),w:250, h:50}
                            CompositionMode:"Source"
                        },
                        {    Text:"Sign In" 
                            TextAttrs:{Color:"#FFff0000", Font:"Small", HAlign:"Center", VAlign:"Center", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, 45), y:Y(gds,65), w:X(gds, 10), h:Y(gds, 10)}
                            CompositionMode:"Source"
                        },
        ]
        return loginForm
    else if (index = 3) then
        loginForm  = [  {   url:scRecList[15] 
                            TargetRect:{x:X(gds, pUX!),y:Y(gds,pUY!),w:300,h:50}
                            CompositionMode:"Source_Over"
                        },
                        {   url:scRecList[15] 
                            TargetRect:{x:X(gds, pPX!),y:Y(gds,pPY!),w:300,h:50}
                            CompositionMode:"Source_Over"
                        },
                        {   Text:userName$ 
                            TextAttrs:{Color:"#FF000000", Font:"Small", HAlign:"Left", VAlign:"VCenter", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, pUX!+2),y:Y(gds,pUY!),w:250, h:50}
                            CompositionMode:"Source"
                        },
                        {   Text:pwd$ 
                            TextAttrs:{Color:"#FF000000", Font:"Small", HAlign:"Left", VAlign:"VCenter", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, pPX!+2),y:Y(gds,pPY!),w:250, h:50}
                            CompositionMode:"Source"
                        },
                        {    Text:"Sign In" 
                            TextAttrs:{Color:"#FF0000ff", Font:"Small", HAlign:"Center", VAlign:"Center", Direction:"LeftToRight"}
                            TargetRect:{x:X(gds, 45), y:Y(gds,65), w:X(gds, 10), h:Y(gds, 10)}
                            CompositionMode:"Source"
                        },
        ]
        return loginForm
    end if
End Function

Function doRegistration2(settings as Object) As Integer

    deviceInfo = CreateObject("roDeviceInfo")
    gds = deviceInfo.GetDisplaySize()
    ht = Y(gds,92) - Y(gds, 12)

    rsp = settings.loginPageDesign
    scRecList = strTokenize(rsp, "^")
    scRecList[15] = "http://1stud.io/" + scRecList[15]
    scRecList[16] = "http://1stud.io/" + scRecList[16]
    scRecList[17] = "http://1stud.io/" + scRecList[17]
    scRecList[18] = "http://1stud.io/" + scRecList[18]

    print scRecList
    
    pLeft! = val(scRecList[1])
    pTop! = val(scRecList[2]) 
    pWidth! = val(scRecList[3])
    pHeight! = val(scRecList[4])
    panelUrl$ = scRecList[18]

    loginForm  = [  {   Color:scRecList[0], CompositionMode:"Source" 
                    },
                    {   url:panelUrl$ 
                        TargetRect:{x:X(gds, pLeft!), y:Y(gds, pTop!), w:X(gds, pWidth!), h:Y(gds, pHeight!)}
                        CompositionMode:"Source"
                    },
                    {   Text:"Member Login" 
                        TextAttrs:{Color:"#000000", Font:"Large", HAlign:"HCenter", VAlign:"VCenter", Direction:"LeftToRight"}
                        TargetRect:{x:CenterAlignX(gds,400),y:Y(gds,25),w:400,h:50}
                        CompositionMode:"Source"
                    },
                    {   Text:scRecList[7] 
                        TextAttrs:{Color:"#FFffffff", Font:"Medium", HAlign:"HCenter", VAlign:"VCenter", Direction:"LeftToRight"}
                        TargetRect:{x:X(gds, val(scRecList[5])),y:Y(gds,val(scRecList[6])),w:150, h:50}
                        CompositionMode:"Source"
                    },
                    {    Text:scRecList[10] 
                        TextAttrs:{Color:"#FFffffff", Font:"Medium", HAlign:"Center", VAlign:"Center", Direction:"LeftToRight"}
                        TargetRect:{x:X(gds, val(scRecList[8])), y:Y(gds,val(scRecList[9])), w:150, h:50}
                        CompositionMode:"Source"
                    },
                    {   url:scRecList[17] 
                        TargetRect:{x:X(gds, 40), y:Y(gds, 65), w:X(gds, 20), h:Y(gds, 10)}
                        CompositionMode:"Source"
                    },
    ]

    canvas = CreateObject("roImageCanvas")
    port = CreateObject("roMessagePort")
    canvas.SetMessagePort(port)

    'Set opaque background
    canvas.clear()
    canvas.SetRequireAllImagesToDraw(true)

    uOrP% = 1
    userName$ = ""
    password$ = ""

    canvas.SetLayer(1, loginForm)
    canvas.SetLayer(2, doRegistration2Screen(gds, scRecList, userName$, password$, 1))
    canvas.Show()

    while(true)
        msg = wait(0, port)
        if (msg <> invalid) then
            'print "doRegistration2 | msg = "; msg.GetMessage() " | index = "; msg.GetIndex() " | type = "; msg.GetType()

            if (msg.GetType()=7) then

                'if (msg.GetIndex()=5) then  'right arrow
                'else if (msg.GetIndex()=4) then  'left arrow
                if (msg.GetIndex()=3) then  'down arrow
                    uOrP% = uOrP% + 1
                    if (uOrP% > 3) uOrP% = 3
                    canvas.SetLayer(2, doRegistration2Screen(gds, scRecList, userName$, password$, uOrP%))

                else if (msg.GetIndex()=2) then  'up arrow

                    uOrP% = uOrP% - 1
                    if (uOrP% < 1) uOrP% = 1
                    canvas.SetLayer(2, doRegistration2Screen(gds, scRecList, userName$, password$, uOrP%))

                else if (msg.GetIndex()=6) then  'OK

                    if (uOrP%=3) then 
                        if (userName$ = "" or password$ = "") then
                            ShowLoginStatusDialog("Username / password can not be blank...")
                        else
                            deviceID =  RegRead("RegToken", "Authentication")
                            if (deviceID <> "") then
                                'VINAY
                                print settings 
                                loginUrl = settings.APIUrl + "?action=login&u=" + StrToHex(userName$) + "&p=" + StrToHex(password$) + "&d=" + GetDeviceESN()
                                'http = NewHttp("http://bbxinc.com/roku/isValidUser/?u=" + userName$ + "&p=" + password$)
                                http = NewHttp(loginUrl)
                                rsp = http.GetToStringWithRetry()
                                'print rsp
                                if (val(rsp) > 0) then
                                    ShowLoginStatusDialog("Connected Successfully")
                                    return 1
                                else
                                    ShowLoginStatusDialog("Invalid Username / password")
                                    return 0
                                endif
                            endif
                        endif
                        
                    elseif (uOrP%=2) then 
                        password$ = GetUserInput(true, "Password", password$)
                        canvas.SetLayer(2, doRegistration2Screen(gds, scRecList, userName$, password$, 2))
                    elseif (uOrP%=1) then 
                        userName$ = GetUserInput(false, "Username", userName$)
                        canvas.SetLayer(2, doRegistration2Screen(gds, scRecList, userName$, password$, 1))
                    endif
                else if (msg.GetIndex()=0) then  'back button
                    ' User has pressed back button  
                    return 0

                endif
            endif
        endif
    end while

End Function

Function doRegistration(settings As Object) As Integer

    sn = GetDeviceESN() 
    if sn = invalid then
        return 0
    endif

    userID = isLinkedBox(sn, settings)
    if (userID > 0) then
        return userID
    endif

    regCode = getRegistrationCode(sn, settings)
    regscreen = displayRegistrationScreen(settings)
    regscreen.SetRegistrationCode(regCode)
    retryInterval = 10
    getNewCodeDuration = 60
    duration = 0

    while true
        print "Wait for " + itostr(retryInterval)
        msg = wait(retryInterval * 1000, regscreen.GetMessagePort())

        if type(msg) = "roCodeRegistrationScreenEvent"
            if msg.isScreenClosed()
                print "Screen closed"
                return 0
            elseif msg.isButtonPressed()
                'print "Button pressed: "; msg.GetIndex(); " " msg.GetData()
                if msg.GetIndex() = 0
                    regCode = getRegistrationCode(sn, settings)
                    regscreen.SetRegistrationCode(regCode)
                else if msg.GetIndex() = 1 
                    return 0
                endif
            endif
        else
            'http = NewHttp(settings.APIUrl + "/linkActivationCode?deviceid=" + sn + "&activationCode=" + regCode)
            http = NewHttp(settings.APIUrl + "?action=registerDevice&d=" + sn + "&a=" + regCode)
            rsp = http.Http.GetToString()
            if (val(rsp) > 0) then
                return val(rsp)
            endif
        endif

        duration = duration + retryInterval
        if (duration >= getNewCodeDuration) then
            regCode = getRegistrationCode(sn, settings)
            regscreen.SetRegistrationCode(regCode)
            duration = 0
        endif
    end while
End Function
