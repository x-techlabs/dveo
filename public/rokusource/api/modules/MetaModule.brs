'Initialization of meta module, setting the appropriate variables
'
'@return tempMetaModule The name of the initialized meta module
function newMetaModule() as Object
    'Creating the tempMetaModule
    tempMetaModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempMetaModule.updateModule = updateMetaModule
    'Initializing the destroy to the appropriate value
    tempMetaModule.destroy = destroyMetaModule
    'The name of the channel or video title
    tempMetaModule.title = ""
    'Setting the functionName to moduleInfo
    tempMetaModule.functionName = "moduleInfo"
    'If the module is Update
    tempMetaModule.isUpdate = false
    'Name of the meta module handle
    tempMetaModule.moduleHandle = metaModuleHandle
    return tempMetaModule
end function

'Meta module is switched off then call this.
'
'@parameter ageModule The name of the age Module
function destroyMetaModule(metaModule as Object)
    metaModule.isUpdate = false
end function


'Refreshing the age module and setting the title.
'
'@param value The name of the value of the meta module
'@param metaModule The name of the meta module
function updateMetaModule(value as Object, metaModule as Object)  
    metaModule.isUpdate = true
    if type(value) = "roAssociativeArray" then  'parameters.meta.title
        if value.DoesExist("title") then
            metaModule.title = value.title
        end if
    else if type(value) = "roBoolean" then
        metaModule.destroy(metaModule)
    end if
end function

'Checking, handling the refreshed datas
'
'@param metaModule The name of the meta module
'@param error Associative array of the error codes
'@return metaHandle(metaModule,error) The name of the error code
function metaModuleHandle(metaModule as Object, error as Object) as Integer
    return metaHandle(metaModule, error)
end function
