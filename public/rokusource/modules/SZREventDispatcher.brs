
Function newSZREventDispatcher() as Object
    
    obj = {}
    
    obj.formatter = newSZRMessageFormatter()
    obj.obsType = GetGlobalAA().SZR_OBS_TYPE_NONE
            
    obj.init = Function() as Object
        return m
    End Function    
        
    obj.closeObserver = Function()
    End Function
    
    obj.getFormatter = Function() as Object
        return m.formatter
    End Function
        
    obj.setObserverType = Function(obsType as integer)
        m.obsType = obsType
        m.formatter.setObserverType(obsType)
    End Function

    obj.setProperties = Function(properties as Object)
        m.formatter.setProperties(properties)
    End Function    

    obj.postMessage = Function(msg as Object)
        if (m.formatter.allRequiredValueChecked())
            getSZRMessageHandlerInstance().addMessage(msg)
        end if
    End Function        
        
    return obj
    
End Function
