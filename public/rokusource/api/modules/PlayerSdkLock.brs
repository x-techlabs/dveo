'Initialization of player sdk lock module, setting the appropriate variables
'
'@return tempPlayerSdkLockModule The name of the initialized player sdk lock module
function newPlayerSdkLockModule() as Object
    'Creating the tempIPLockModule
    tempPlayerSdkLockModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempPlayerSdkLockModule.updateModule = updatePlayerSdkModule
    'Initializing the destroy to the appropriate value
    tempPlayerSdkLockModule.destroy = destroyPlayerSdkReject
    'The name of the lock name
    tempPlayerSdkLockModule.lockName = ""
    'Setting the functionName to reject
    tempPlayerSdkLockModule.functionName = "reject"
    'If the module is Update
    tempPlayerSdkLockModule.isUpdate = false
    'Name of the player sdk lock module handle
    tempPlayerSdkLockModule.moduleHandle = playerSdkModuleHandle
    return tempPlayerSdkLockModule
end function

'Player sdk module is switched off then call this.
'
'@parameter playerSdkModule The name of the player sdk lock module
function destroyPlayerSdkReject(playerSdkModule as Object)

end function

'Refreshing the plyaer sdk lock module and setting the variables.
'
'@param value The name of the value of the player sdk lock module
'@param playerSdkModule The name of the player sdk lock module
function updatePlayerSdkModule(value as Object, playerSdkModule as Object)
    if type(value) = "roBoolean"
        playerSdkModule.destroy(ipLockModule)
    else
        playerSdkModule.isUpdate = true
    end if
end function

'Handling the refreshed datas
'
'@param ipLockModule The name of the ip lock module
'@param error Associative array of the error codes
'@return ipLockHandle(ipLockModule,error) The error code of the handle
function playerSdkModuleHandle(playerSdkModule as Object, error as Object) as Integer
    return playerSdkLockHandle(playerSdkModule,error)
end function
