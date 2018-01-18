'Initialization of sessionAuthLock lock module, setting the appropriate variables
'
'@return tempSessionAuthLock The name of the initialized sessionauth lock module
function newSessionAuthLockModule() as Object
    'Creating the tempSessionAuthLock
    tempSessionAuthLock = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempSessionAuthLock.updateModule = updateSessionAuthLockModule
    'Initializing the destroy to the appropriate value
    tempSessionAuthLock.destroy = destroyAuthLockReject
    'The name of the lock name
    tempSessionAuthLock.lockName = ""
    'Setting the functionName to reject
    tempSessionAuthLock.functionName = "reject"
    'If the module is Update
    tempSessionAuthLock.isUpdate = false
    'Name of the sessionauth lock module handle
    tempSessionAuthLock.moduleHandle = sessionAuthLockModuleHandle
    return tempSessionAuthLock
end function

'SessionauthLock module is switched off then call this.
'
'@parameter sessionModule The name of the session module
function destroyAuthLockReject(sessionModule as Object)

end function

'Refreshing the sessionauth lock module and setting the variables.
'
'@param value The name of the value of the sessionauth lock module
'@param sessionModule The name of the sessionauth lock module
function updateSessionAuthLockModule(value as Object, sessionModule as Object)
    if type(value) = "roAssociativeArray" then
        sessionModule.isUpdate = true
        temp = createobject("roAssociativeArray")
        temp = value   
        temp.Next()
        lockValue = temp.Next()   
        if lockValue <> invalid then
            sessionModule.lockName = lockValue
            sessionModule.isUpdate = true
            sessionModule.functionName = "reject"
        end if
    else if type(value) = "roBoolean"
        sessionModule.destroy(sessionModule)
    end if
end function

'Handling the refreshed datas
'
'@param sessionAuthLockModule The name of the sessionauth lock module
'@param error Associative array of the error codes
'@return sessionAuthLockHandle(sessionAuthLockModule, error) The error code of the handle
function sessionAuthLockModuleHandle(sessionAuthLockModule as Object, error as Object) as Integer
    return sessionAuthLockHandle(sessionAuthLockModule, error)
end function
