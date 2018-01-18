
Function getSZRVariablesInstance() as Object
    globals = GetGlobalAA()
    if globals.szrVariablesInstance = Invalid
        globals.szrVariablesInstance = newSZRVariablesInstance().init()        
    end if
    return globals.szrVariablesInstance    
End Function


Function newSZRVariablesInstance() as Object
    
    obj = {}
    
    obj.STREAMLYZER_MESSAGE_SPEC_VERSION = m.SZR_MESSAGE_SPEC_VERSION
    obj.SYSTEM_PROPERTIES = {}
    obj.DEFAULT_INPUT_PROPERTIES = {} 
    obj.DEFAULT_INPUT_PROPERTIES_MEDIA = {}
    obj.ACCEPTABLE_PROPERTIES = {}
        
    obj.szrid = ""    
    obj.lastSessionUpdateTime = getSZRTimeObj()
    
                    
    obj.init = Function() as Object
        globals = GetGlobalAA()
        
        '
        m.SYSTEM_PROPERTIES["v"] = m.STREAMLYZER_MESSAGE_SPEC_VERSION

        m.SYSTEM_PROPERTIES["osn"] = m.STREAMLYZER_MESSAGE_SPEC_VERSION
        m.SYSTEM_PROPERTIES["osv"] = m.STREAMLYZER_MESSAGE_SPEC_VERSION
        m.SYSTEM_PROPERTIES["devv"] = getSZRUtilInstance().getDeviceVersion()
        m.SYSTEM_PROPERTIES["devm"] = getSZRUtilInstance().getDeviceModel()
        
        m.SYSTEM_PROPERTIES["lang"] = m.getCurrentLanguage()
        m.SYSTEM_PROPERTIES["lrg"] = m.getCurrentRegion()        
        
        '
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_CUSTOMER_KEY]   = globals.SZR_VALUE_DEFAULT_UNSET
                    
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_USER_ID]        = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_USER_TYPE]      = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_GENDER]         = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_YEAR_OF_BIRTH]  = globals.SZR_VALUE_DEFAULT_UNSET
        
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_SERVICE_TYPE]   = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_SESSION_ID]     = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_STREAMING_SERVER_NAME] = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_ABTEST_MARK]    = globals.SZR_VALUE_DEFAULT_UNSET

        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_PLATFORM_NAME]   = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_APPLICATION_NAME]    = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_APPLICATION_VERSION] = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_PLAYER_PLATFORM_VERSION] = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES[globals.SZR_MEDIA_PLAYER_VERSION]    = globals.SZR_VALUE_DEFAULT_UNSET                
            
        '
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_LIVE]              = globals.SZR_VALUE_LIVE_FALSE
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_MOVIE_ID]          = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_MOVIE_CATEGORY]    = globals.SZR_VALUE_DEFAULT_UNSET
        
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_BIT_RATE]          = "0"
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_RESOLUTION]        = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_LIVE_CHANNEL_NAME] = globals.SZR_VALUE_DEFAULT_UNSET
        
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_MOVIE_SUBCATEGORY] = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_MOVIE_CONTENTS_PROVIDER]   = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_MOVIE_RATE]        = globals.SZR_VALUE_DEFAULT_UNSET
        
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_SERIES_NAME]       = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_EPISODE_NAME]      = globals.SZR_VALUE_DEFAULT_UNSET
        m.DEFAULT_INPUT_PROPERTIES_MEDIA[globals.SZR_THUMBNAIL_IMAGE]   = globals.SZR_VALUE_DEFAULT_UNSET
                                
        '        
        m.ACCEPTABLE_PROPERTIES[globals.SZR_CUSTOMER_KEY]   = "SZR_CUSTOMER_KEY"
                    
        m.ACCEPTABLE_PROPERTIES[globals.SZR_USER_ID]        = "SZR_USER_ID"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_USER_TYPE]      = "SZR_USER_TYPE"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_GENDER]         = "SZR_GENDER"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_YEAR_OF_BIRTH]  = "SZR_YEAR_OF_BIRTH"
        
        m.ACCEPTABLE_PROPERTIES[globals.SZR_SERVICE_TYPE]   = "SZR_SERVICE_TYPE"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_SESSION_ID]     = "SZR_SESSION_ID"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_STREAMING_SERVER_NAME] = "SZR_STREAMING_SERVER_NAME"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_ABTEST_MARK]    = "SZR_ABTEST_MARK"
        
        m.ACCEPTABLE_PROPERTIES[globals.SZR_LIVE]               = "SZR_LIVE"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_MOVIE_ID]           = "SZR_MOVIE_ID"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_MOVIE_CATEGORY]     = "SZR_MOVIE_CATEGORY"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_LIVE_CHANNEL_NAME]  = "SZR_LIVE_CHANNEL_NAME"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_MOVIE_CONTENTS_PROVIDER]     = "MOVIE_CONTENTS_PROVIDER"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_THUMBNAIL_IMAGE]    = "SZR_THUMBNAIL_IMAGE"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_SERIES_NAME]        = "SZR_SERIES_NAME"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_EPISODE_NAME]       = "SZR_EPISODE_NAME"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_MOVIE_SUBCATEGORY]  = "SZR_MOVIE_SUBCATEGORY"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_MOVIE_RATE]         = "SZR_MOVIE_RATE"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_BIT_RATE]           = "SZR_BIT_RATE"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_RESOLUTION]         = "SZR_RESOLUTION"
        
        m.ACCEPTABLE_PROPERTIES[globals.SZR_PLAYER_PLATFORM_VERSION]    = "SZR_PLAYER_PLATFORM_VERSION"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_MEDIA_PLAYER_VERSION]   = "SZR_MEDIA_PLAYER_VERSION"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_PLATFORM_NAME]          = "SZR_PLATFORM_NAME"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_APPLICATION_NAME]       = "SZR_APPLICATION_NAME"
        m.ACCEPTABLE_PROPERTIES[globals.SZR_APPLICATION_VERSION]    = "SZR_APPLICATION_VERSION"        
                                           
        return m
    End Function         
    
    
    obj.getCurrentLanguage = Function() as String
        return getSZRUtilInstance().getLanguage()
    End Function        
    
        
    obj.getCurrentRegion = Function() as String
        return getSZRUtilInstance().getRegion()       
    End Function    
    
    
    obj.requiredProperties = Function(obsType as integer) as Object        
        globals = GetGlobalAA()      
          
        mDic = {}
        
        mDic[globals.SZR_CUSTOMER_KEY] = false
        
        mDic[globals.SZR_USER_ID] = false
        mDic[globals.SZR_USER_TYPE] = false
        
        mDic[globals.SZR_STREAMING_SERVER_NAME] = false        
        mDic[globals.SZR_SESSION_ID] = false
        mDic[globals.SZR_ABTEST_MARK] = false
        
        mDic[globals.SZR_MEDIA_PLAYER_VERSION] = false
        mDic[globals.SZR_PLATFORM_NAME] = false
        mDic[globals.SZR_APPLICATION_NAME] = false
        mDic[globals.SZR_APPLICATION_VERSION] = false

        if (obsType = globals.SZR_OBS_TYPE_PLAYBACK_EVENT)
            mDic[globals.SZR_LIVE] = false
            mDic[globals.SZR_MOVIE_ID] = false
            mDic[globals.SZR_MOVIE_CATEGORY] = false
            mDic[globals.SZR_BIT_RATE] = false
            mDic[globals.SZR_RESOLUTION] = false
            mDic[globals.SZR_LIVE_CHANNEL_NAME] = false
        else if (obsType = globals.SZR_OBS_TYPE_SHARED_CONTENTS_EVENT)
            mDic[globals.SZR_LIVE] = false
            mDic[globals.SZR_MOVIE_ID] = false
            mDic[globals.SZR_MOVIE_CATEGORY] = false
            mDic[globals.SZR_LIVE_CHANNEL_NAME] = false
        end if

        return mDic                
           
    End Function
    
    
    obj.updateStreamlyzerSessionId = Function()
        globals = GetGlobalAA()
        
        updateSZRID = false
        currentTime = getSZRTimeObj().now()        
        if (m.lastSessionUpdateTime.seconds = 0 and m.lastSessionUpdateTime.milliseconds = 0)
            updateSZRID = true
        else 
            timeDiff = currentTime.getDiff(m.lastSessionUpdateTime)
            diffValue = timeDiff.timeValue()
            if (diffValue > globals.SZR_SESSION_TIME_OUT)
                updateSZRID = true
            end if
        end if
        
        if (updateSZRID)
            m.szrid = getSZRUtilInstance().getUUID()
        end if
        m.lastSessionUpdateTime = currentTime
    End Function    
    
        
    obj.getSZRSessinId = Function() as String
        m.updateStreamlyzerSessionId()
        return m.szrid
    End Function
                
    return obj
    
End Function
