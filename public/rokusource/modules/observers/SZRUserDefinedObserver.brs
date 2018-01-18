
Function newSZRUserDefinedObserver() as Object
    
    obj = {}
    
    obj.eventDispatcher = newSZREventDispatcher().init() 
            
    obj.init = Function()
        globals = GetGlobalAA()        
        m.eventDispatcher.setObserverType(globals.SZR_OBS_TYPE_USER_DEFINED_EVENT)
        return m
    End Function    
    
    
    obj.closeObserver = Function()
        m.eventDispatcher.closeObserver()
    End Function
    
    
    obj.setProperties = Function(properties as Object)
        m.eventDispatcher.setProperties(properties)
    End Function

        
    obj.postCountableEvent = Function(eventGroup as String, cntEventName as String) 
        globals = GetGlobalAA() 
        
        checkParm = false        
    
        if getSZRUtilInstance().isEmptyStr(cntEventName) = false
            checkParm = true            
            if getSZRUtilInstance().isEmptyStr(eventGroup)
                evtGroup = globals.SZR_VALUE_DEFAULT_UNSET
            end if
        end if
        
        if checkParm              
            getSZRMessageHandlerInstance().addMessage( m.eventDispatcher.formatter.getUserDefinedCountEvent(getSZRTimeObj().now(), eventGroup, cntEventName) )
        else
            SZRLogger("Invalid Parameters : User Defined - Count ")        
        end if        
    End Function
        
        
    obj.postSumEvent = Function(eventGroup as String, sumEventName as String, sumEventValue as integer)     
        globals = GetGlobalAA() 
        
        checkParm = false        
    
        if getSZRUtilInstance().isEmptyStr(sumEventName) = false
            checkParm = true            
            if getSZRUtilInstance().isEmptyStr(eventGroup)
                evtGroup = globals.SZR_VALUE_DEFAULT_UNSET
            end if
        end if
        
        if checkParm              
            getSZRMessageHandlerInstance().addMessage( m.eventDispatcher.formatter.getUserDefinedSumEvent(getSZRTimeObj().now(), eventGroup, sumEventName, sumEventValue) )
        else
            SZRLogger("Invalid Parameters : User Defined - Sum ")        
        end if        
    End Function
        
        
    obj.postRevenueEvent = Function(eventGroup as String, revenueEventName as String, revenueEventValue as double)
        globals = GetGlobalAA() 
        
        checkParm = false        
    
        if getSZRUtilInstance().isEmptyStr(revenueEventName) = false
            checkParm = true            
            if getSZRUtilInstance().isEmptyStr(eventGroup)
                evtGroup = globals.SZR_VALUE_DEFAULT_UNSET
            end if
        end if
        
        if checkParm              
            getSZRMessageHandlerInstance().addMessage( m.eventDispatcher.formatter.getUserDefinedRevenuEvent(getSZRTimeObj().now(), eventGroup, revenueEventName, revenueEventValue) )
        else
            SZRLogger("Invalid Parameters : User Defined - Revenue ")        
        end if               
    End Function
        
    return obj
    
End Function
