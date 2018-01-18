'******************************************************
'**  RokuChannelMaker Template 
'**  2014
'**  Copyright (c) 2014 RokuChannelMaker.com All Rights Reserved.
'******************************************************

'***********************************************************
'** Create and show the video screen.  The video screen is
'** a special full screen video playback component.  It 
'** handles most of the keypresses automatically and our
'** job is primarily to make sure it has the correct data 
'** at startup. We will receive event back on progress and
'** error conditions so it's important to monitor these to
'** understand what's going on, especially in the case of errors
'***********************************************************  
Function AnalyticsObject(episode, settings)
    obj = {}

    if (settings.analytics="1") then
        obj.obs = newSZRPlaybackObserver().init()    
        szr = getSZRCommonValuesInstance(settings)
        szr.episodeToContentProperties(episode)

        obj.obs.setProperties(szr.getBasicProperties())
        obj.obs.setProperties(szr.contentsAtIndex(0))
    end if
    return obj
End Function

Function CanViewRequest(episode As Object, settings As Object)
    canViewUrl = settings.APIUrl + "?action=canView&v=" + episode.subscription + "&d=" + GetDeviceESN()
    http = NewHttp(canViewUrl)
    rsp = http.GetToStringWithRetry()
    response = strtokenize(rsp, "|")
    if (response.count() <> 4) then
        ShowDialog1Button("Network Error", "Unknown error while Authenticating", "OK")
        return ["crash", "0", "", ""]
    endif
    return response
End Function

Function IsAuthorizedToWatch(episode As Object, settings As Object)
    if (episode.viewing="free") return 1
    if (episode.viewing="") return 1

    response = CanViewRequest(episode, settings)
    if (response[0] = "crash") return 0
    if (response[0] = "success") return 1

    ' success|0||
    ' error|1|Unknown Device|device not registered
    ' error|2|Subscription Error|Not authorized to watch this video
    ' error|N|title|description

    if (strtoi(response[1])=1) then
        if (ShowLoginScreen(settings)=1) then
            ' Registration successful, check permission for this video again
            response = CanViewRequest(episode, settings)
            if (response[0]="success") return 1
        endif
    endif
    ShowDialog1Button(response[2], response[3], "OK")
    return 0
End Function

Function showVideoScreen(episode As Object, settings As Object)

    if type(episode) <> "roAssociativeArray" then
        print "invalid data passed to showVideoScreen"
        return -1
    endif
    print "****** In showVideoScreen"
    print episode

    if (IsAuthorizedToWatch(episode, settings)=0) return 0


    obj = AnalyticsObject(episode, settings)
    if (settings.appType = "ustream") then
        if (episode.ContentType = "live") then
            episode.streamurl = episode.streamurls[0]
            displayVideoLive(episode, settings, "hls")
            return 0       
        else
            playRecorded(episode.contentid)
            return 0       
        endif
    endif


    trackerID = TrackVideoStart(episode, settings)
    timer = CreateObject("roTimespan")
    timer.Mark()

    port = CreateObject("roMessagePort")
    screen = CreateObject("roVideoScreen")
    screen.SetMessagePort(port)

    screen.Show()
    screen.SetPositionNotificationPeriod(30)
    screen.SetContent(episode)
    screen.Show()

    while true
        msg = wait(0, port)

        if (timer.TotalSeconds() > 60) then
             TrackVideoEnd(trackerID)
             timer.Mark()
        endif  

        if type(msg) = "roVideoScreenEvent" then
            if (settings.analytics="1") obj.obs.updateVideoScreenEvent(msg)
            'print "showVideoScreen | msg = "; msg.getMessage() " | index = "; msg.GetIndex()
            if msg.isScreenClosed()
                print "Screen closed"
                exit while
            else if msg.isRequestFailed()
     
                print "Video request failure: "; msg.GetIndex(); " " msg.GetData()
                RegWrite(episode.contentId, "0")
            else if msg.isFullResult()
                print "Playback completed"
                RegWrite(episode.contentId, "0")
            else if msg.isStatusMessage()
                'print "Video status: "; msg.GetIndex(); " " msg.GetData() 

            else if msg.isButtonPressed()
                print "Button pressed: "; msg.GetIndex(); " " msg.GetData()
            else if msg.isPlaybackPosition() then
                nowpos = msg.GetIndex()
                RegWrite(episode.ContentId, nowpos.toStr())
            else
                print "Unexpected event type: "; msg.GetType()
            end if
        else
            print "Unexpected message class: "; type(msg)
        end if
        if (settings.analytics="1") SZRPluginTimerUpdate()
    end while
    TrackVideoEnd(trackerID)
End Function

