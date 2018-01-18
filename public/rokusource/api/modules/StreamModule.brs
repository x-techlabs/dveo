'Initialization of stream module, setting the appropriate variables
'
'@return tempStreamModule The name of the initialized stream module
function newStreamModule() as Object
    'Creating the tempStreamModule
    tempStreamModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempStreamModule.updateModule = updateStreamModule
    'Initializing the destroy to the appropriate value
    tempStreamModule.destroy = destroyStreamModule
    'The name of the stream status
    tempStreamModule.streamStatus = ""
    'The name of the recorded video url
    tempStreamModule.streamName = ""
    'The name of the channel url
    tempStreamModule.streamUrl = ""
    'The name of the stream type for example, Recorded,Oncline,Offline
    tempStreamModule.streamType = ""
    'Setting the functionName to moduleInfo
    tempStreamModule.functionName = "moduleInfo"
    'If the module is Update
    tempStreamModule.isUpdate = false
    'Name of the stream module handle
    tempStreamModule.moduleHandle = streamModuleHandle
    return tempStreamModule
end function

'appId,appVersion kamel case

'Stream module is switched off then call this.
'
'@parameter streamModule The name of the stream module
function destroyStreamModule(streamModule as Object)

end function

'Refreshing the stream module and setting the variables.
'
'@param value The name of the value of the stream module
'@param streamModule The name of the stream module
function updateStreamModule(value as Object, streamModule as Object)
    streamModule.isUpdate = true
    if type(value) = "roArray" then
        for each stream in value
            if stream.DoesExist("streams") then
                for each streams in stream.streams
                    if type(streams) = "roAssociativeArray" then
                        if streams.DoesExist("streamName") then
                            streamModule.streamStatus = "RECORDED"                                  
                            streamModule.streamName = streams.streamName
                            streamModule.streamType = "recorded"
                        end if
                    end if
                end for                         
            end if
            if stream.DoesExist("url") then
                streamModule.streamStatus = "ONLINE"
                streamModule.streamType = "channel"
                streamModule.streamUrl = stream.url
            end if                  
        end for
    else if type(value) = "roBoolean"
        streamModule.destroy(streamModule)
    else
        streamModule.streamType = "channel"
        streamModule.streamStatus = "OFFLINE"
    end if
end function

'Checking, handling the refreshed datas
'
'@param ageModule The name of the age module
'@param error Associative array of the error codes
'@return channelOfflineError The name of the channel offline error code
'@return formatSupportedError The name of the format not supported error code
'@return nothingChanges The error code given when nothing changes
function streamModuleHandle(streamModule as Object,error as Object) as Integer
    if streamModule.streamStatus = "OFFLINE" then
        return error.channelOfflineError
    else if streamModule.streamName = "" and streamModule.streamStatus = "RECORDED" then
        return error.formatSupportedError
    else if streamModule.streamName = "" and streamModule.streamUrl = "" then
        return streamErrorHandle()
    else
        return error.nothingChanges
    end if
end function
