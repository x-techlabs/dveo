'Initialization of cluster geolock module, setting the appropriate variables
'
'@return tempGeoLockModule Name of the initialized geo lock module
function newGeoLockModule() as Object
    'Creating the tempGeoLockModule
    tempGeoLockModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempGeoLockModule.updateModule = updateGeolockModule
    'Initializing the destroy to the appropriate value
    tempGeoLockModule.destroy = destroyGeoLockReject
    'The name of the lock name
    tempGeoLockModule.lockName = ""
    'Setting the functionName to reject
    tempGeoLockModule.functionName = "reject"
    'If the module is Update
    tempGeoLockModule.isUpdate = false
    'Name of the geo lock module handle
    tempGeoLockModule.moduleHandle = geoLockModuleHandle
    return tempGeoLockModule
end function

'Geo lock module is switched off then call this.
'
'@parameter geoLockModule The name of the geo lock module
function destroyGeoLockReject(geoLockModule as Object)

end function

'Refreshing the geo lock module and setting the variables.
'
'@param value The name of the value of the geo lock module
'@param clusterRejectModule The name of the geo lock module
function updateGeolockModule(value as Object, geoLockModule as Object)  
    if type(value) = "roAssociativeArray" then
        geoLockModule.isUpdate = true
        temp = createobject("roAssociativeArray")
        temp = value
        temp.Next()
        lockValue = temp.Next()   
        if lockValue <> invalid then
            geoLockModule.lockName = lockValue
        end if
    else if type(value) = "roBoolean"
        geoLockModule.destroy(geoLockModule)
    end if
end function

'Handling the refreshed datas
'
'@param geoLockModule The name of the geo lock module
'@param error Associative array of the error codes
'@return geoLockHandle(error) The error code of the handle
function geoLockModuleHandle(geoLockModule as Object, error as Object) as Integer
    return geoLockHandle(error)
end function
