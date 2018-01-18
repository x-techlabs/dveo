
Function newSZRPageReferrerObserver() as Object
    
    obj = {}
    
    obj.eventDispatcher = newSZREventDispatcher().init() 
    
            
    obj.init = Function()
        globals = GetGlobalAA()        
        m.eventDispatcher.setObserverType(globals.SZR_OBS_TYPE_PAGE_REFERRER_EVENT)
        return m
    End Function    
    
    
    obj.closeObserver = Function()
        m.eventDispatcher.closeObserver()
    End Function
    
    
    obj.setProperties = Function(properties as Object)
        m.eventDispatcher.setProperties(properties)
    End Function
    
        
    obj.postPageReferrerEvent = Function(hostName as String, referrerPagePath as String, currentPagePath as String)    
        globals = GetGlobalAA() 
        
        checkParm = false        
        parm = {}
        
        if getSZRUtilInstance().isEmptyStr(hostName)
            parm[globals.SZR_KEY_REFERRER_HOSTNAME] = globals.SZR_VALUE_DEFAULT_UNSET
        else
            checkParm = true
            parm[globals.SZR_KEY_REFERRER_HOSTNAME] = hostName
        end if
        
        if getSZRUtilInstance().isEmptyStr(referrerPagePath)
            parm[globals.SZR_KEY_REFERRER_PAGE_PATH] = globals.SZR_VALUE_DEFAULT_UNSET
        else
            checkParm = true
            parm[globals.SZR_KEY_REFERRER_PAGE_PATH] = referrerPagePath
        end if
        
        if getSZRUtilInstance().isEmptyStr(currentPagePath)
            parm[globals.SZR_KEY_CURRENT_PAGE_PATH] = globals.SZR_VALUE_DEFAULT_UNSET
        else
            checkParm = true
            parm[globals.SZR_KEY_CURRENT_PAGE_PATH] = currentPagePath
        end if
              
        if checkParm              
            getSZRMessageHandlerInstance().addMessage( m.eventDispatcher.formatter.getPageReferrerEvent(getSZRTimeObj().now(), parm) )
        else
            SZRLogger("Invalid Parameters : Page Referrer")        
        end if        
    End Function   
        
    return obj
    
End Function
