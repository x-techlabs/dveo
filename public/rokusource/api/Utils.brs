'It initializes the modules,error and the constans to the value specified.
sub initialization(streamId as String, application as String)
    m.initialization = true
    if (m.registeredModules = invalid) then
        m.registeredModules = initModules() 
    else
        initChangeModule(m.registeredModules)
    end if
    m.timer = createObject("roTimespan")
    m.startTime = m.timer.TotalSeconds()
    m.playingProcessing = createObject("roList")
    m.moduleError = initError()
    m.const = initConstans()
    m.lastStatus = 1
    m.error = 0
    m.protocolVersion = "1"
    m.channelId = streamId
    m.application = application
    if (type(m.apiKey) = "Invalid" and type(m.apiKeyVersion) = "Invalid") then
        init("", "")
    end if
end sub

'Error-code initialization. Initializes error values to the specified value
'
'@return error The name of the error codes
function initError() as Object
    error = createObject("roAssociativeArray")
    error.exitError = 0
    error.passwordLockError = -1
    error.privateVideoError = -2
    error.formatSupportedError = -3
    error.requestFailedError = -4
    error.channelOfflineError = -6
    error.recordedOfflineError = -7
    error.skip = -10
    error.nothingChanges = -11
    error.unsupportedMediaError = -13
    error.noFunctionDefinition= -14
    error.supportedMediaError = -15
    error.ageLockError = -16
    return error 
end function

function initConstans() as Object
    request = createObject("roUrlTransfer")
    constans = createObject("roAssociativeArray")
    
    constans.pageUrl = request.UrlEncode("http://ustream.tv")
    
    return constans
end function

'Handling video events. Calling playing function.
'
'@param videoMessage The name of the video event
'@return exitError The error code of screen closing
'@return invalidEvent The name of the invalid event error code
function videoEventHandle(videoMessage as Object) as Integer
    if type(videoMessage) = "roVideoScreenEvent" then
        if videoMessage.isScreenClosed() then
            umsSendStatusHandle(1)
            return m.moduleError.exitError
        else if videoMessage.isRequestFailed()
            umsSendStatusHandle(1)
            return m.moduleError.skip
        else if videoMessage.isStreamStarted()
            return umsSendStatus(true)
        else if videoMessage.isPaused()
            pushPlayingArray(0)
        else if videoMessage.isResumed()
            pushPlayingArray(1)
        endif
    end if
    return m.moduleError.nothingChanges
end function

'Play/Stop flood array
'
'@param value The name of this playing true or playing false
sub pushPlayingArray(value as Integer)
    if (m.playingProcessing.Count() > 0) then
        if (m.playingProcessing.GetTail() <> value) then
            m.playingProcessing.RemoveTail()
        end if
    else
        m.playingProcessing.AddTail(value)        
    end if
    
end sub

'Generation of current date
'
'@return dateTime The name of current date
function nowDate() as String
    date = CreateObject("roDateTime")
    dateTime = ""
    dateTime = dateTime + right(str(date.getYear()), 4)
    dateTime = dateTime + "."
    if date.getMonth() > 9 then
        dateTime = dateTime + right(str(date.GetMonth()), 2)
    else
        dateTime = dateTime + "0"
        dateTime = dateTime + right(str(date.GetMonth()), 1)
    end if
    dateTime = dateTime + "."
    if date.GetDayOfMonth() > 9 then
        dateTime = dateTime + right(str(date.GetDayOfMonth()), 2)
    else
        dateTime = dateTime + "0"
        dateTime = dateTime + right(str(date.GetDayOfMonth()), 1)
    end if
    dateTime = dateTime + "."

    return   dateTime
end function

'Age ot birthday conversion
'
'@param age The name of the user's age
'@return dateTime The name of the birthday
function getAgeToDate(age as Integer) as String
    if age < 0 then return ""

    date = CreateObject("roDateTime")
    dateTime = ""
    dateTime = dateTime + right(str(date.getYear()-age), 4)
    dateTime = dateTime + "."
    if date.getMonth() > 9 then
        dateTime = dateTime + right(str(date.GetMonth()), 2)
    else
        dateTime = dateTime + "0"
        dateTime = dateTime + right(str(date.GetMonth()), 1)
    end if
    dateTime = dateTime + "."
    if date.GetDayOfMonth() > 9 then
        dateTime = dateTime + right(str(date.GetDayOfMonth()), 2)
    else
        dateTime = dateTime + "0"
        dateTime = dateTime + right(str(date.GetDayOfMonth()), 1)
    end if
    dateTime = dateTime + "."
    
    return   dateTime
end function

'Birthday to age conversion
'
'@param birthday The name of the user's birthday
'@return date.getYear() - year The name of the age
function getDateToAge(birthDay as String) as Integer
    date = CreateObject("roDateTime")
    year = strtoi(Left(birthDay, 4))
    month = strtoi(mid(birthDay, 6, 2))
    day = strtoi(mid(birthDay, 9, 2))
    
    if month > date.getMonth() then
        return (date.getYear() - year) - 1
    end if
    
    if day > date.getDayOfMonth() then
        return (date.getYear() - year) - 1
    end if
    
    return date.getYear() - year
end function

'Checking the date dots and the length of the date. If the date os greater than 2014 and less than 1914, then it is not a valid date
'
'@param birthday The name of the user's birthday
'@return true Date is not valid
'@return false Date is valid
function validDate(birthDay as String) as Boolean
    if len(birthDay) <> 11 then return false
    
    if mid(birthDay, 5, 1) <> "." or mid(birthDay, 8, 1) <> "." or mid(birthDay, 11, 1) <> "." then return false
    
    if strtoi(Left(birthDay, 4)) < 1914 or strtoi(mid(birthDay, 6, 2)) < 1 or strtoi(mid(birthDay, 6, 2)) > 12 or strtoi(mid(birthDay, 9, 2)) > 31 or strtoi(mid(birthDay, 9, 2)) < 1 then return false
    
    return true
end function

'Comparison of two dates and date format checking.
'
'@param maxBirthDay The name of the ums birthday
'@param birthDay The name of the user birthday
'@return true Not valid date or maxBirthDay is less than birthDay
'@return false Date is valid
function greaterBirthDay(maxBirthDay as Object, birthDay as Object) as Boolean
    if len(maxBirthDay) = 0 then return false

    if not(validDate(birthDay)) then return true

    if strtoi(Left(maxBirthDay, 4)) < strtoi(Left(birthDay, 4)) then       
        return true
    else if strtoi(Left(maxBirthDay, 4)) = strtoi(Left(birthDay, 4)) then
        if strtoi(mid(maxBirthDay, 6, 2)) < strtoi(mid(birthDay, 6, 2)) then
            return true
        else if strtoi(mid(maxBirthDay, 6, 2)) = strtoi(mid(birthDay, 6, 2))
            if strtoi(mid(maxBirthDay, 9, 2)) < strtoi(mid(birthDay, 9, 2))
                return true
            end if
        end if
    end if
    return false
end function

'Creating the associative array necessary for the video playing
'
'@param streamTitle The name of the url which is necessary for the video playing
'@param streamformat The stream's format can be, for example, mp4 or hls
'@return videoclip Name of the associative array necessary for playing the stream
function getVideoClip(streamTitle as String, streamFormat as String) as Object
    if m.registeredModules["stream"].streamStatus = "RECORDED" then
        streamUrl = m.registeredModules["stream"].streamName
    else
        streamUrl = m.registeredModules["stream"].streamUrl
    end if
    bitrates = [0, 0]
    urls = [streamUrl, streamUrl]
    qualities = ["SD", "HD"]
    title = streamTitle

    videoClip = CreateObject("roAssociativeArray")
    videoClip.StreamBitrates = bitrates
    videoClip.StreamUrls = urls
    videoClip.StreamQualities = qualities
    videoClip.StreamFormat = streamFormat
    videoClip.Title = title

    return videoClip
end function
