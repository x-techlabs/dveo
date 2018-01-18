
Function getSZRMessageHandlerInstance() as Object
    globals = GetGlobalAA()
    if globals.szrMessageHandlerInstance = Invalid
        globals.szrMessageHandlerInstance = newSZRMessageHandlerInstance().init()
    end if
    return globals.szrMessageHandlerInstance
End Function


Function newSZRMessageHandlerInstance() as Object

    obj = {}
        
    obj.serverUrl = "http://mfb1.streamlyzer.com:8070"
    obj.isSendingMsg = false
        
    obj.currentCustomerKey = CreateObject("roString")
    obj.customerKeyMap = CreateObject("roAssociativeArray")

    obj.request = CreateObject("roUrlTransfer")
    obj.messagePort = CreateObject("roMessagePort")
    obj.messageQueue = CreateObject("roArray", 0, true)    
        
    obj.init = Function() as Object
        m.request.SetPort(m.messagePort)
        return m        
    End Function
    
    
    obj.addMessage = Function(msg as Object)
        m.messageQueue.Push(msg)        
    End Function
        
        
    obj.processMessage = Function()
        if (m.messageQueue.count() > 0) 
            msg = m.messageQueue.GetEntry(0)
            
            if (msg["ckey"] = Invalid)
                SZRLogger("no ckey")
                m.messageQueue.shift()
            else               
                if m.isSendingMsg
                else                
                    ckey = msg["ckey"]  
                    if (m.customerKeyMap[ckey] = Invalid)
                        SZRLogger("Verifying Customer Key [" + ckey + "]")
                        m.isSendingMsg = true                        
                        m.currentCustomerKey = ckey                                                                      
                        m.request.SetUrl(m.serverUrl + "/check?ckey=" + m.request.escape(ckey))
                        m.request.AsyncGetToString()
                    else if (m.customerKeyMap[ckey])
                        SZRLogger("Send Log Message")
                        
                        m.isSendingMsg = true
                        logMsg = m.msgToLog(msg)
                        'SZRLogger(logMsg)
                        
                        m.request.SetUrl(m.serverUrl + "/?sclav=" + getSZRVariablesInstance().STREAMLYZER_MESSAGE_SPEC_VERSION + "&log=" + logMsg)
                        m.request.AsyncGetToString()                        
                        'SZRLogger(m.request.getUrl())
                        
                        m.messageQueue.shift()
                    else 
                        SZRLogger("Invalid CKEY [" + ckey + "]") 
                        m.messageQueue.shift()
                    end if
                end if                
            end if
        end if
        
        msg = wait(1, m.messagePort)        
        if type(msg) = "roUrlEvent"
            if m.currentCustomerKey <> ""               
                if msg.GetString().trim() = "true"
                    m.customerKeyMap[m.currentCustomerKey] = true
                else 
                    m.customerKeyMap[m.currentCustomerKey] = false
                end if
               m.currentCustomerKey = ""
            end if         
            m.isSendingMsg = false
        end if
                        
    End Function
            
            
    obj.msgToLog = Function(msg as Object) as String
        return m.request.escape(getSZRUtilInstance().associativeArrayToJSON(msg))
    end Function    
    
    
    obj.init()    
    return obj
    
End Function
