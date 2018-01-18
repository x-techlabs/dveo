'Initialization of connectionId module, setting the appropriate variables
'
'@return tempConnectionIdModule The name of the initialized connection id module
function newConnectionIdModule() as Object
    'Creating the tempIPLockModule
    tempConnectionIdModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempConnectionIdModule.updateModule = updateConnectionIdModule
    'Initializing the destroy to the appropriate value
    tempConnectionIdModule.destroy = destroyConnectionIdModule
    'The name of the lock name
    
    tempConnectionIdModule.connectionId = ""
    tempConnectionIdModule.host = ""
    
    tempConnectionIdModule.lockName = ""

    'Setting the functionName to reject
    tempConnectionIdModule.functionName = "tracking"
    'If the module is Update
    tempConnectionIdModule.isUpdate = false
    'Name of the connectionId module handle
    tempConnectionIdModule.moduleHandle = connectionIdModuleHandle
    return tempConnectionIdModule
end function

'ConnectionId module is switched off then call this.
'
'@parameter connectionIdModule The name of the connectionId module
function destroyConnectionIdModule(connectionIdModule as Object)

end function

'Refreshing the ip lock module and setting the variables.
'
'@param value The name of the value of the ip lock module
'@param ipLockModule The name of the ip lock module
function updateConnectionIdModule(value as Object, connectionIdModule as Object)
    if type(value) = "roString" then
        connectionIdModule.isUpdate = true
        connectionIdModule.connectionId = value
    else if type(value) = "roAssociativeArray" then
        connectionIdModule.isUpdate = true
        if value.DoesExist("connectionId") then
            connectionIdModule.connectionId = value.connectionId
        end if
        if value.DoesExist("host") then
            connectionIdModule.host = value.host
        end if
    else if type(value) = "roBoolean" then
        connectionIdModule.destroy(connectionIdModule)
    end if
end function

'Handling the refreshed datas
'
'@param playerSdkModule The name of the player sdk module
'@param error Associative array of the error codes
'@return ipLockHandle(ipLockModule,error) The error code of the handle
function connectionIdModuleHandle(playerSdkModule as Object, error as Object) as Integer
    return error.skip
end function
