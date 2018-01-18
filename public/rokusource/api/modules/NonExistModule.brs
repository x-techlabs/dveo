'Initialization of non existent module, setting the appropriate variables
'
'@return tempNonExistentModule The name of the initialized non existent module
function newNonExistentModule() as Object
    'Creating the tempNonExistentModule
    tempNonExistentModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempNonExistentModule.updateModule = updateNonExistentModule
    'Initializing the destroy to the appropriate value
    tempNonExistentModule.destroy = destroyNonExistReject
    'The name of the lock name
    tempNonExistentModule.lockName = ""
    'Setting the functionName to reject
    tempNonExistentModule.functionName = "reject"
    'If the module is Update
    tempNonExistentModule.isUpdate = false
    'Name of the ip lock module handle
    tempNonExistentModule.moduleHandle = nonexistentRejectModuleHandle
    return tempNonExistentModule
end function

'Non existent module is switched off then call this.
'
'@parameter nonExistentModule The name of the non existent module
function destroyNonExistReject(nonExistentModule as Object)

end function

'Refreshing the non existent module and settings the values.
'
'@param value The name of the value of the non existent module
'@param nonExistentModule The name of the non existent module
function updateNonExistentModule(value as Object, nonExistentModule as Object)
    if type(value) = "roAssociativeArray"  
        temp = createobject("roAssociativeArray")
        temp = value   
        temp.Next()
        lockValue = temp.Next()   
        if lockValue <> invalid then            
            nonExistentModule.lockName = lockValue
            nonExistentModule.isUpdate = true
            nonExistentModule.functionName = "reject"
        end if
    else if type(value) = "roBoolean"
        nonExistentModule.destroy(clusterRejectModule)
    end if
end function

'Checking, handling the refreshed datas
'
'@param nonExistentModule The name of the non existent module
'@param error Associative array of the error codes
'@return nonexistenModuleHandle(nonExistentModule, error) The error code of the handle
function nonexistentRejectModuleHandle(nonExistentModule as Object, error as Object) as Integer
    nonexistenModuleHandle(nonExistentModule, error)
end function
