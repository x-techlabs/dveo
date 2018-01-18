'Initialization of referrer lock module, setting the appropriate variables
'
'@return tempReferrerLockModule The name of the initialized referrer lock module
function newReferrerLockModule() as Object
    'Creating the tempReferrerLockModule
    tempReferrerLockModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempReferrerLockModule.updateModule = updateReferrerlockModule
    'Initializing the destroy to the appropriate value
    tempReferrerLockModule.destroy = destroyReferrerReject
    'The name of the lock name
    tempReferrerLockModule.lockName = ""
    'Setting the functionName to reject
    tempReferrerLockModule.functionName = "reject"
    'If the module is Update
    tempReferrerLockModule.isUpdate = false
    'Name of the referrer lock module handle
    tempReferrerLockModule.moduleHandle = reffererlockRejectHandle
    return tempReferrerLockModule
end function

'Referrer module is switched off then call this.
'
'@parameter referrerLockModule The name of the referrer lock module
function destroyReferrerReject(referrerLockModule as Object)

end function


'Refreshing the referrer lock module and setting the variables.
'
'@param value The name of the value of the referrer lock module
'@param referrerLockModule The name of the referrer lock module
function updateReferrerlockModule(value as Object, referrerLockModule as Object)  
    if type(value) = "roAssociativeArray" then
        referrerLockModule.isUpdate = true
        temp = createobject("roAssociativeArray")
        temp = value
        temp.Next()
        lockValue = temp.Next() 
        if lockValue <> invalid then
            referrerLockModule.lockName = lockValue
            referrerLockModule.functionName = "reject"
        end if
    else if type(value) = "roBoolean"
        referrerLockModule.destroy(referrerLockModule)
    end if
end function

'Handling the refreshed datas
'
'@param referrerLockModule The name of the referrer lock module
'@param error Associative array of the error codes
'@return reffererlockRejectHandle(referrerLockModule, error) The error code of the handle
function reffererlockRejectModuleHandle(referrerLockModule as Object, error as Object) as Integer
    return reffererlockRejectHandle(referrerLockModule, error)
end function
