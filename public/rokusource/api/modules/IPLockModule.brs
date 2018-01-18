'Initialization of ip lock module, setting the appropriate variables
'
'@return tempIPLockModule The name of the initialized ip lock module
function newIPLockModule() as Object
    'Creating the tempIPLockModule
    tempIPLockModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempIPLockModule.updateModule = updateIPlockModule
    'Initializing the destroy to the appropriate value
    tempIPLockModule.destroy = destroyIpLockReject
    'The name of the lock name
    tempIPLockModule.lockName = ""
    'Setting the functionName to reject
    tempIPLockModule.functionName = "reject"
    'If the module is Update
    tempIPLockModule.isUpdate = false
    'Name of the ip lock module handle
    tempIPLockModule.moduleHandle = ipLockModuleHandle
    return tempIPLockModule
end function

'Ip lock module is switched off then call this.
'
'@parameter ipLockModule The name of the ip lock module
function destroyIpLockReject(ipLockModule as Object)

end function

'Refreshing the ip lock module and setting the variables.
'
'@param value The name of the value of the ip lock module
'@param ipLockModule The name of the ip lock module
function updateIPlockModule(value as Object, ipLockModule as Object)  
    if type(value) = "roAssociativeArray" then
        ipLockModule.isUpdate = true
        temp = createobject("roAssociativeArray")
        temp = value
        temp.Next()
        lockValue = temp.Next()   
        if lockValue <> invalid then
            ipLockModule.lockName = lockValue
            ipLockModule.functionName = "reject"
        end if
    else if type(value) = "roBoolean"
        ipLockModule.destroy(ipLockModule)
    end if
end function

'Handling the refreshed datas
'
'@param ipLockModule The name of the ip lock module
'@param error Associative array of the error codes
'@return ipLockHandle(ipLockModule,error) The error code of the handle
function ipLockModuleHandle(ipLockModule as Object, error as Object) as Integer
    return ipLockHandle(ipLockModule, error)
end function
