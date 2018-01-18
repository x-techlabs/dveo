'Initializing modules sent by ums
'
'@return registeredModules The name of the ums modules
function initModules() as Object
    registeredModules = createobject("roAssociativeArray")
    registeredModules["viewers"] = newViewerModule()
    registeredModules["meta"] = newMetaModule()
    registeredModules["stream"] = newStreamModule()
    registeredModules["ageLock"] = newAgeModule()
    registeredModules["geoLock"] = newGeoLockModule()
    registeredModules["passwordLock"] = newPasswordLockModule()
    registeredModules["nonexistent"] = newNonExistentModule()
    registeredModules["sessionAuthLock"] = newSessionAuthLockModule()
    registeredModules["cluster"] = newClusterRejectModule()
    registeredModules["referrerLock"] = newReferrerLockModule()
    registeredModules["ipLock"] = newIPLockModule()
    registeredModules["playerSdkLock"] = newPlayerSdkLockModule()
    registeredModules["connectionId"] = newConnectionIdModule()
    return registeredModules
end function

'these modules require reinitialization on media change
sub initChangeModule(modules as Object)
    modules["cluster"] = newClusterRejectModule()
    modules["ageLock"] = newAgeModule()
    modules["connectionId"] = newConnectionIdModule()
end sub

'configure api key and bundle name
sub init(apiKey as String, applicationBundleName as String)
    configureWithApiKey(apiKey)
    m.applicationBundleName = applicationBundleName
end sub

'Iterates through modules and if the module is isUpdate then calls the moduleHandle.
'
'@param registeredModule The name of this ums modules
'@return registeredModuleError The error code of the module
'@return skip The error code of skip.
function moduleIterator(registeredModule as Object) as Integer
    modules = createObject("roAssociativeArray") 'FIXME This seems to be dead code. This is overwritten in the next line...
    modules = registeredModule
    modules.Reset()
    while modules.IsNext()
        key = modules.Next()
        if registeredModule[key].isUpdate then
            registeredModule[key].isUpdate = false
            registeredModuleError = registeredModule[key].moduleHandle(registeredModule[key], m.moduleError)
            if registeredModuleError <> m.moduleError.nothingChanges then return registeredModuleError
        end if
    end while
    return m.moduleError.nothingChanges
end function

'Http Request initialization and remoting server through URL.
'Password check handling
'
'@param streamId The names of this channelId or recorded video Id.
'@param password Password entered by the user
'@param application Stream can be channel or recorded video
'@return passwordLockError The name of the passwordLockError code
'@return skip The error code of skip
'@return umsAge The name of the ageLock module
function isPasswordFine(streamId as String, password as String, application as String) as Integer
    m.registeredModules["connectionId"].connectionId = ""
    counter = 0
    while (counter < 2)
        m.umsConnection = newUmsConnection(application, streamId, password)
        request = m.umsConnection.createUmsRequest()
    
        umsSimpletext = doUmsRequest(request)
        moduleError = handleUmsResponse(umsSimpletext)
        
        counter = counter + 1
    end while

    if  m.registeredModules["passwordLock"].isUpdate then
        m.registeredModules["passwordLock"].isUpdate = false
        return m.moduleError.passwordLockError
    end if
    
    if m.registeredModules.DoesExist("ageLock") then
        if m.registeredModules["ageLock"].umsAge = 0 then
            return m.moduleError.skip
        else
            return m.registeredModules["ageLock"].umsAge
        end if
    end if
end function


'Date contains 8 number, if it is less than 8, then it is not a valid date. I and J are loop variables.
'Counts the numbers in the date
'
'@param date Date entered by the user
'@return true Date is not valid
'@return false Date is valid
function notvalidDateNumber(date as String) as Boolean
    i = 0
    j = 1
    dateNumberCounter = 0
    while j < 11
        i = 0
        while i < 10
            if right(str(i),1) = mid(date,j,1) then
                dateNumberCounter = dateNumberCounter + 1
                exit while
            end if
            i = i +1
        end while
        j = j + 1
    end while
    if dateNumberCounter = 8 then return false else return true
end function

'Pong response to ping
'Non standard characters about use urlEncode
function sendPong() as Integer
    connectionActive = {
        cmd: "pong",
        args: []
    }
    pongRequest = m.umsConnection.createUmsRequest()
    jsonString = SimpleJSONBuilder(connectionActive)
    urlEncode = pongRequest.UrlEncode(jsonString)
    umsSendUrl = "cmds="
    umsSendUrl = umsSendUrl + urlEncode
    debugLogUmsRequestWithPost(pongRequest.getUrl(), "sendPong", umsSendUrl)
     
    if (pongRequest.AsyncPostFromString(umsSendUrl) = true)
        event = wait(3000, pongRequest.getMessagePort())
        return umsModuleHandle(event, m.video)
    end if
    return m.moduleError.nothingChanges
end function

'If video event is pause or resume, then calls playing function
'Non standard characters about use urlEncode
'
'@param value The name of this playing true or playing false
function umsSendStatus(value as Boolean) as Integer
    moviesisPlay = {
        cmd: "playing",
        args: [value]
    }
    playingRequest = m.umsConnection.createUmsRequest()
    jsonString = SimpleJSONBuilder( moviesisPlay )
    urlEncode = playingRequest.UrlEncode(jsonString)
    umsSendUrl = "cmds="
    umsSendUrl = umsSendUrl + urlEncode
    debugLogUmsRequestWithPost(playingRequest.getUrl(), "umsSendStatus", umsSendUrl)

    if (playingRequest.AsyncPostFromString(umsSendUrl) = true)
        event = wait(2000, playingRequest.getMessagePort())
        return umsModuleHandle(event, m.video)
    end if

    return m.moduleError.nothingChanges
end function

'Play/Stop flood handle
'
'@param value The name of this playing true or playing false
function umsSendStatusHandle(value as Integer) as Integer
    if value = 0 then
        if (m.playingProcessing.Count() > 0) then
            m.lastStatus = m.playingProcessing.RemoveHead()
            if (m.lastStatus = 1) then
                return umsSendStatus(true)
            else
                return umsSendStatus(false)
            end if
        end if
    else
        if m.lastStatus <> 0 and value = 1 then
            return umsSendStatus(false)
        else if value = 2
            return umsSendStatus(true)
        end if
    end if
    return m.moduleError.nothingChanges
end function
