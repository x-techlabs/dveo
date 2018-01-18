'Initialization of viewer module, setting the appropriate variables
'
'@return tempViewerModule The name of the initialized viewer module
function newViewerModule() as Object
    'Creating the tempViewerModule
    tempViewerModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempViewerModule.updateModule = updateViewerModule
    'Initializing the destroy to the appropriate value
    tempViewerModule.destroy = destroyView
    'The name of the viewer count
    tempViewerModule.viewer = 0
    'Setting the functionName to moduleInfo
    tempViewerModule.functionName = "moduleInfo"
    'If the module is Update
    tempViewerModule.isUpdate = false
    'Name of the viewers module handle
    tempViewerModule.moduleHandle = viewersModuleHandle
    return tempViewerModule
end function

'Viewers module is switched off, then call this.
'
'@parameter viewerModule The name of the viewers module
function destroyView(viewerModule as Object)
     viewerModule.isUpdate = false
     '
     'Can not be removed without user interaction
     '
end function

'Refreshing the viewers module and setting the variables.
'
'@param value The name of the value of the viewers module
'@param viewerModule The name of the viewers module
function updateViewerModule(value as Object, viewerModule as Object)
    viewerModule.isUpdate = true
    if type(value) = "roInt" then 
        viewerModule.viewer = value
    else if type(value) = "roBoolean" then
        viewerModule.destroy(viewerModule)
    end if
end function

'Handling the refreshed datas
'
'@param viewerModule The name of the viewers module
'@param error Associative array of the error codes
'@return viewersHandle(viewerModule,error) The error code of the handle
function viewersModuleHandle(viewerModule as Object, error as Object) as Integer
    return viewersHandle(viewerModule,error)
end function
