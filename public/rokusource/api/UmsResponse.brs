'parse ums response and set up modules accordingly
'
'@param jsonString The response in JSON
'@return String The last cmd received
function parseUmsResponse(jsonString as String) as String
    json = ParseJSON(jsonString)
    if (type(json) = "roArray")
        for each array in json
            if (array.DoesExist("cmd")) then
                cmd = array.cmd
                'print "cmd: ";array.cmd
                if (array.DoesExist("args") and (array.cmd = "moduleInfo" or array.cmd = "reject")) then
                    for each args in array.args
                        if type(args) = "roAssociativeArray" then
                            param = createObject("roAssociativeArray")
                            param = args
                            param.Next()
                            while param.IsNext()
                                key = param.Next()
                                if m.registeredModules.DoesExist(key) then
                                    m.registeredModules[key].updateModule(param[key],m.registeredModules[key])
                                end if
                            end while
                        end if
                    end for
                else if (array.DoesExist("args") and array.cmd = "tracking") then
                    trackingargs = array.args[0]
                    if (m.registeredModules.DoesExist("connectionId")) then
                        m.registeredModules["connectionId"].updateModule(trackingargs,m.registeredModules["connectionId"])
                    end if
                end if
            end if
        end for
    else if (type(json) = "roAssociativeArray")
        return json.functionName
    end if
    return cmd
end function

'Parse the response and set variables based on result
'
'@param jsonString The response in JSON
'@return Integer The error code
function handleUmsResponse(jsonString as String) as Integer
	m.umsFunctionName = parseUmsResponse(jsonString)
    moduleError = moduleIterator(m.registeredModules)
    if m.umsFunctionName = "noFunctionDefinition"
        return m.moduleError.noFunctionDefinition
    else
    	return moduleError
    end if
end function

'Updating and processing modules.Sending pong request.
'
'@parameter umsMessage The name of the roUrlTransfer event
'@parameter screen The name of the screen
'@return moduleError The error code of the module.
'@return skip The error code of skip.
function umsModuleHandle(umsMessage as Object, screen as Object) as Integer
    if (type(umsMessage) = "roUrlEvent") then
        umsSimpleText = umsMessage.GetString()
        print "ums response: ";umsSimpletext

        m.umsFunctionName = parseUmsResponse(umsSimpletext)
        if m.umsFunctionName = "close" or m.umsFunctionName = "reject" then
            screen.close()
            moduleError = moduleIterator(m.registeredModules)
            return moduleError
        else if m.umsFunctionName = "ping"
            return sendPong()
        else if m.umsFunctionName = "moduleInfo"
            moduleError = moduleIterator(m.registeredModules)
            return moduleError
        end if
    end if
    return m.moduleError.nothingChanges
end function
