'Initialization of age module, setting the appropriate variables
'
'@return tempAgeModule The name of the initialized age module
function newAgeModule() as Object
  'Creating the tempAgeModule
	tempAgeModule = createObject("roAssociativeArray")
	'Initializing the updateModule to the appropriate value
    tempAgeModule.updateModule = updateAgeModule
    'Initializing the destroy to the appropriate value
    tempAgeModule.destroy = destroyAgeModule
	'The name of the ums age
    tempAgeModule.umsAge = 0
	'The name of the user age
    tempAgeModule.userAge = 0
	'The name of the ums birthday
    tempAgeModule.umsMaximumBirthDate = ""
	'The name of the user birthday
    tempAgeModule.userMaximumBirthDate = nowDate()
	'if the age sent by the ums is greater than the age given by the user
    tempAgeModule.isAge = false
	'if the birthday sent by the ums is greater than the birthday given by the user
    tempAgeModule.isBirthDay = false
    'Setting the functionName to reject
    tempAgeModule.functionName = "reject"
	'If the module is Update
    tempAgeModule.isUpdate = false
    'Name of the Age module handle
    tempAgeModule.moduleHandle = ageModuleHandle
    return tempAgeModule
end function

'Age Module is switched off then call this.
'
'@parameter ageModule The name of the age module
function destroyAgeModule(ageModule as Object)
    ageModule.isUpdate = false
    '
    'Can not be removed without user interaction
    '
end function

'Refreshing the age module and setting the age.
'
'@param value The name of the value of the age module
'@param ageModule The name of the ageModule
function updateAgeModule(value as Object, ageModule as Object)
    ageModule.isUpdate = true
    if type(value) = "roString" then
        ageModule.isAge = true
        ageModule.isBirthDay = false
        ageModule.umsAge = strtoi(value)
    else if type(value) = "roAssociativeArray" then
        if value.DoesExist("maximumBirthDate") then
            ageModule.isAge = false
            ageModule.isBirthDay = true
            ageModule.umsMaximumBirthDate = value.maximumBirthDate
        end if   
    else if type(value) = "roBoolean" then
        ageModule.destroy(ageModule)
    end if  
end function

'Checking, handling the refreshed datas
'
'@param ageModule The name of the age module
'@param error Associative array of the error codes
'@return nothingChanges The error code given when nothing changes
'@return skip The error code of skip
'@return ageHandle(ageModule,error) The name of the error code
'@return birthDayHandle(ageModule,error) The error code of the handle
function ageModuleHandle(ageModule as Object, error as Object) as Integer
    ageL = false
    if ageModule.userAge > 0 and greaterBirthDay(ageModule.userMaximumBirthDate, nowDate()) then 
        ageL = true
    end if
    if ageModule.umsAge > ageModule.userAge or greaterBirthDay(ageModule.umsMaximumBirthDate, ageModule.userMaximumBirthDate) then
        continueWithAgeConfirmed()
        if ageModule.isAge then
            if ageModule.umsAge > ageModule.userAge then
                return ageHandle(ageModule, error)
            end if
        else if ageModule.isBirthDay then
            if greaterBirthDay(ageModule.umsMaximumBirthDate, ageModule.userMaximumBirthDate) then
                return birthDayHandle(ageModule, error)
            end if
        end if
        if ageL then
            return error.ageLockError
        end if
        return error.nothingChanges
    end if
    return error.nothingChanges
end function
