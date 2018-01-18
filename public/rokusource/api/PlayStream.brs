'PLaying the stream. Handling rejects, Calling "playing" function and handling ping-pong requests.
'Create video object and set the header. 
'Videoclip implements meta-data descriptions.
'
'@param request remoting server through URL
'@param streamFormat The stream's format can be, for example, mp4 or hls
'@return videoEventError The error code of the videoEvent
'@return moduleError The error code of the module.
function playStream(request as Object, streamFormat as String) as Integer
    if m.registeredModules["stream"].streamStatus <> "RECORDED" then
        streamCheckError = streamCheck(m.registeredModules["stream"].streamUrl, request, m.moduleError)
        if streamCheckError <> m.moduleError.supportedMediaError then return streamCheckError
    end if
    videoClip = getVideoClip(m.registeredModules["meta"].title,streamFormat)
    videoPort = CreateObject("roMessagePort")
    m.video = CreateObject("roVideoScreen")
    m.video.SetHeaders(getHttpHeader())
    m.video.setMessagePort(videoPort)
    m.video.SetContent(videoClip)
    m.video.show()
    
    while true 
        if (m.timer.TotalSeconds() - m.startTime > 3) then
            m.startTime = m.timer.TotalSeconds()
            umsSendStatusError = umsSendStatusHandle(0)
            if umsSendStatusError <> m.moduleError.nothingChanges then
                return umsSendStatusError
            end if
        end if
        if (request.AsyncGetToString()) then
            debugLogUmsRequest(request.GetUrl(), "playStream async")
            umsMessage = wait(1, request.GetPort())
            moduleError = umsModuleHandle(umsMessage, m.video)
            if moduleError <> m.moduleError.nothingChanges then
                if m.umsFunctionName = "moduleInfo" then
                    umsSendStatusHandle(1)
                end if
                m.video.Close()
                return moduleError
            end if
        end if
                  
        videoMessage = wait(1, m.video.GetMessagePort())
        videoEventError = videoEventHandle(videoMessage)
        if videoEventError <> m.moduleError.nothingChanges then
            return videoEventError
        end if
    end while
end function

'Initialization of the streamObject, and stream status checking
'
'@param streamUrl The name of the url which is necessary for the video playing
'@param request remoting server through URL
'@param errors The name of the error codes
'@return error The error code of the module
function streamCheck(streamUrl as String, request as Object, errors as Object)
    streamObject = Setup()
    streamObject.setup()
    error = errors.unsupportedMediaError
    while error = errors.unsupportedMediaError
        error = streamObject.eventLoop(streamUrl, request, errors)
    end while
    return error
end function

'initialization of the videoplayer object and setting the background.
'
'@return this The necessary object for the videoplaying.
function Setup() as Object
    constSecondInterval = 30
    date = createObject("roDateTime")
    this = {
        port:      CreateObject("roMessagePort")
        progress:  0 'buffering progress
        canvas:    CreateObject("roImageCanvas") 'user interface
        player:    CreateObject("roVideoPlayer")
        setup:     SetupFullscreenCanvas
        imagesCounter: 0
        modulo : 0
        paint:     PaintFullscreenCanvas
        eventloop: EventLoop
        secondInterval : constSecondInterval
        firstTime : date.getSeconds() + constSecondInterval
    }
    
    this.canvas.SetMessagePort(this.port)
    this.canvas.SetLayer(0, { Color: "#000000" })
    this.canvas.Show()

    this.player.SetMessagePort(this.port)
    this.player.SetLoop(true)
    this.player.SetPositionNotificationPeriod(1)
    this.player.SetDestinationRect(this.targetRect)
    this.player.Play()
    this.playingPrev = this.playing
    return this
end function


'Video buffering,module handling
'
'@param streamUrl The name of the url which is necessary for the video playing
'@param request remoting server through URL
'@param errors The name of the error codes
'@return moduleError The error code of the module.
'@return supportedMediaError The error code of the supported media
'@return unsupportedMediaError The error code of the unsupported media
function eventLoop(streamUrl as String, request as Object, errors as Object) as Integer
    contentList = []
    contentList.Push({
        Stream: { url: streamUrl }
        StreamFormat: "hls"
    }) 
        
    m.player.SetContentList(contentList)
    m.player.Play()
    while true
        m.paint()
        if (request.AsyncGetToString()) then
            debugLogUmsRequest(request.GetUrl(), "eventLoop async")
            umsMessage = wait(1, request.GetPort())
            moduleError = umsModuleHandle(umsMessage, m.canvas)
            if moduleError <> errors.nothingChanges then
                return moduleError
            end if
        end if
        
        msg = wait(1, m.port)
        if msg <> invalid then
            if msg.GetMessage() = "The format is not supported or the media is corrupt." then
                return errors.unsupportedMediaError
            else if msg.GetMessage() = "Download segment info" then
                return errors.supportedMediaError
            else if msg.isScreenClosed() then
                exit while
            else if msg.isRemoteKeyPressed()
                index = msg.GetIndex()
                if index = 0 then
                    exit while
                end if
            end if
        end if
    end while
end function

'Setting video background
sub setupFullscreenCanvas()
    m.paint()
end sub

'Setting video background
sub paintFullscreenCanvas()
    splash = []
    list = []
    date = createObject("roDateTime")
    
    m.imagesCounter = m.imagesCounter + 1
    count = Str(m.modulo)
    if m.modulo < 10 then
        count = right(count,1)
    else
        count = right(count,2)
    end if
    sleep(40)
    stageString = "pkg:/images/loading (1)-"+count+".png"
    deviceInfo = CreateObject("roDeviceInfo")
    if deviceInfo.GetDisplayMode() = "720p":
        progress_bar = {TargetRect: {x: 620, y: 400, w: 60, h: 60}, url: stageString}
    else:
        progress_bar = {TargetRect: {x: 350, y: 270, w: 40, h: 40}, url: stageString}
    end if
    color = "#262626"
    splash.Push({
        TargetRect: m.targetRect
    })
    list.Push({
        Text: "Please Wait!"
        TextAttrs: { font: "large", color: "#0707070" }
        TargetRect: m.textRect
    })
    
    m.modulo = m.modulo + 1
    if m.imagesCounter mod 35 = 0 then
        m.modulo = 0
    end if
    list.Push(progress_bar)

    m.canvas.SetLayer(0, { Color: color, CompositionMode: "Source" })
    if (splash.Count() > 0)
        m.canvas.SetLayer(2, list)
    endif
end sub
