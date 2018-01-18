'Initialization of password lock module, setting the appropriate variables
'
'@return tempPasswordLockModule The name of the initialized password lock module
function newPasswordLockModule() as Object
    'Creating the tempPasswordLockModule
    tempPasswordLockModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempPasswordLockModule.updateModule = updatePasswordlockModule
    'Initializing the destroy to the appropriate value
    tempPasswordLockModule.destroy = destroyPasswordLockModule
    'Name of the password entered by the user
    tempPasswordLockModule.password = ""
    'The name of the lock name
    tempPasswordLockModule.lockName = ""
    'Setting the functionName to reject
    tempPasswordLockModule.functionName = "reject"
    'If the module is Update
    tempPasswordLockModule.isUpdate = false
    'Name of the password lock module handle
    tempPasswordLockModule.moduleHandle = passwordRejectHandle
    return tempPasswordLockModule
end function

'Password module is switched off then call this.
'
'@parameter passwordModule The name of the password lock module
function destroyPasswordLockModule(passwordModule as Object)
    passwordModule.isUpdate = false
    '
    'Can not be removed without user interaction
    '
end function

'Refreshing the password lock module and setting the variables.
'
'@param value The name of the value of the password lock module
'@param passwordModule The name of the password lock module
function updatePasswordlockModule(value as Object, passwordModule as Object)
    passwordModule.isUpdate = true
    if type(value) = "roAssociativeArray" then
        temp = createobject("roAssociativeArray")
        temp = value   
        temp.Next()
        lockValue = temp.Next()       
        if lockValue <> invalid then
            passwordModule.lockName = lockValue
            passwordModule.functionName = "reject"
        end if
    else if type(value) = "roBoolean" then
        passwordModule.destroy(passwordModule)
    end if
end function

'Handling the refreshed datas and Compares passwords
'
'@param passwordLockModule The name of the password lock module
'@param error Associative array of the error codes
'@return skip The error code of skip
'@return exitError The name of the error code, when the password screen is closed
function passwordRejectHandle(passwordLockModule as Object, error as Object) as Integer
    passwordLockModule.password = getPassword()
    if passwordLockModule.password <> "" then
        continueWithPassword(passwordLockModule.password)                    
        return error.passwordLockError
    else
       return error.exitError
    end if
end function
