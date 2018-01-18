
Function newSZRSharedContentsObserver() as Object
    
    obj = {}
    
    obj.eventDispatcher = newSZREventDispatcher().init() 
            
            
    obj.init = Function()
        globals = GetGlobalAA()        
        m.eventDispatcher.setObserverType(globals.SZR_OBS_TYPE_SHARED_CONTENTS_EVENT)
        return m
    End Function    
    
    
    obj.closeObserver = Function()
        m.eventDispatcher.closeObserver()
    End Function
    
    
    obj.setProperties = Function(properties as Object)
        m.eventDispatcher.setProperties(properties)
    End Function
    
        
    obj.postSharedContentsEvent = Function(evtGroup as String, destination as String)    
        globals = GetGlobalAA() 
        
        checkParm = false        
        
        if getSZRUtilInstance().isEmptyStr(destination) = false
            checkParm = true            
            if getSZRUtilInstance().isEmptyStr(evtGroup)
                evtGroup = globals.SZR_VALUE_DEFAULT_UNSET
            end if
        end if
                              
        if checkParm              
            getSZRMessageHandlerInstance().addMessage( m.eventDispatcher.formatter.getSharedContentsEvent(getSZRTimeObj().now(), evtGroup, destination) )
        else
            SZRLogger("Invalid Parameters : Shared Contents")        
        end if        
    End Function   
        
    return obj
    
End Function
