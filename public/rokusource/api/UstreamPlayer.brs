'Error and module initialization. It sets the error and the application fields to 0 and to channel. It initializes the channel id's value to the value specified.
'Error handling
'
'@param id The name of the channelId.
sub playChannel(id as String)
    initialization(id, "channel")
    while(true)
        m.error = streamManagement(id, m.registeredModules["passwordLock"].password, m.application, "hls")
        if (m.error = m.moduleError.requestFailedError) then 
            m.error = requestFailed()
        end if
                                   
        if (m.error = m.moduleError.channelOfflineError) then
            m.error = channelOffline()
        end if
        
        if (m.error <> m.moduleError.skip) then
            m.registeredModules["connectionId"].connectionId = ""
        end if
        
        if (m.error = m.moduleError.noFunctionDefinition) then
            m.protocolVersion = "0"
        end if

        if (m.error = m.moduleError.exitError) then exit while
    end while
end sub

'Error and module initialization. It sets the error and application fields to 0 and recorded. It initializes the channel id's value to the value specified.
'Error handling
'
'@param id The name of this recorded video id.
sub playRecorded(id as String)
    initialization(id, "recorded")
    while(true)
        m.error = streamManagement(id, m.registeredModules["passwordLock"].password, m.application, "mp4")
        if (m.error = m.moduleError.privateVideoError) then
            m.error = privateVideo()
        end if
        
        if (m.error = m.moduleError.requestFailedError) then 
            m.error = requestFailed()
        end if
        
        if (m.error = m.moduleError.formatSupportedError) then
            m.error = formatNotSupported()
        end if
        
        if (m.error = m.moduleError.recordedOfflineError) then
            m.error = recordedVideoNonExist()
        end if
        
        if (m.error = m.moduleError.noFunctionDefinition) then
            m.protocolVersion = "0"
        end if
        
        if (m.error <> m.moduleError.skip) then
            m.registeredModules["connectionId"].connectionId = ""
        end if
        
        if (m.error = m.moduleError.exitError) then
            exit while
        end if
    end while
end sub

'Http Request initialization and remoting server through URL.
'Creates the ums connection and sets the header.Module processing and reject handling. Start playing.
'
'@param streamId The names of this channelId or recorded video Id.
'@param password Password entered by the user.
'@param applicaton Stream can be channel or recorded video
'@param streamFormat The stream's format can be, for example, mp4 or hls
'@return moduleError The error code of the module.  
'@return playStream(request, streamFormat)  The error code of the module or videoevent.
function streamManagement(streamId as String, password as String, application as String, streamFormat as String) as Integer
    m.umsConnection = newUmsConnection(application, streamId, password)

    request = m.umsConnection.createUmsRequest()
    umsSimpletext = doUmsRequest(request)

    moduleError = handleUmsResponse(umsSimpletext)

    if moduleError <> m.moduleError.nothingChanges then
        return moduleError
    end if
    return playStream(request, streamFormat)
end function

'Compares and handles passwords.
'
'@param password Password entered by the user
sub continueWithPassword(password as String)
    m.error = isPasswordFine(m.channelId, password, m.application)
    if m.error = m.moduleError.passwordLockError then
        incorrectPassword()
    end if
end sub

'With your Ustream-provided apiKey before any play command.
sub configureWithApiKey(apiKey as String)
    m.apiKey = apiKey
end sub

'Age and Birthday entered by the user.Age to birthday and birthday to age conversion.
sub continueWithAgeConfirmed()
    if m.registeredModules["ageLock"].isBirthDay then
        m.registeredModules["ageLock"].userMaximumBirthDate = getBirthDay()
        m.registeredModules["ageLock"].userAge = getDateToAge(m.registeredModules["ageLock"].userMaximumBirthDate)
    else if m.registeredModules["ageLock"].isAge then
        m.registeredModules["ageLock"].userAge = getAge()
        m.registeredModules["ageLock"].userMaximumBirthDate = getAgeToDate(m.registeredModules["ageLock"].userAge)
    end if
end sub
