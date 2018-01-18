'Initialization of cluster module, setting the appropriate variables
'
'@return tempClusterRejectModule The name of the initialized cluster module
function newClusterRejectModule() as Object
    'Creating the tempClusterRejectModule
    tempClusterRejectModule = createObject("roAssociativeArray")
    'Initializing the updateModule to the appropriate value
    tempClusterRejectModule.updateModule = updateClusterRejectModule
    'Initializing the destroy to the appropriate value
    tempClusterRejectModule.destroy = destroyClusterReject
    'The name of the ums host name
    tempClusterRejectModule.host = ""
    'The name of the ums name name
    tempClusterRejectModule.name = ""
    'Setting the functionName to reject
    tempClusterRejectModule.functionName = "reject"
    'If the module is Update
    tempClusterRejectModule.isUpdate = false
    'Name of the cluster reject module handle
    tempClusterRejectModule.moduleHandle = clusterRejectHandle
    return tempClusterRejectModule
end function

'Cluster Module is switched off then call this.
'
'@parameter clusterRejectModule The name of the cluster module
function destroyClusterReject(clusterRejectModule as Object)

end function

'Refreshing the cluster module and setting the variables.
'
'@param value The name of the value of the cluster module
'@param clusterRejectModule The name of the cluster module
function updateClusterRejectModule(value as Object, clusterRejectModule as Object)
    if type(value) = "roAssociativeArray" then
        clusterRejectModule.isUpdate = true
        if value.DoesExist("host") then
           clusterRejectModule.host = value["host"]
        end if
        if value.DoesExist("name") then
            clusterRejectModule.name = value["name"]
        end if
    else if type(value) = "roBoolean"
        clusterRejectModule.destroy(clusterRejectModule)
    end if
end function

'Handling the refreshed datas
'
'@param clusterModule The name of the cluster module
'@param error Associative array of the error codes
'@return skip The error code of skip
function clusterRejectHandle(clusterModule as Object, error as Object) as Integer
    if (type(clusterModule.host) = "String") then
        if len(clusterModule.host) > 0 then
            return error.skip
        else
            return error.skip
        end if
    end if
end function
