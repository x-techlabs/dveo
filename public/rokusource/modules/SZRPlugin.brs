

Function initSZRPlugin() as Object

    m.SZR_MESSAGE_SPEC_VERSION          = "0.9.1"
    m.SZR_PLUGIN_REVISION_VERSION       = "0"
        
    m.SZR_TIME_UNIT_SECOND              = 1000
    m.SZR_TIME_UNIT_MINUTE              = 60 * m.SZR_TIME_UNIT_SECOND
        
    m.SZR_TIME_INTERVAL_FOR_CHECKING    = 200
    m.SZR_TIME_INTERVAL_OF_VIEWHOUR     = m.SZR_TIME_UNIT_MINUTE - m.SZR_TIME_INTERVAL_FOR_CHECKING
    m.SZR_TIME_INTERVAL_OF_BUFFERING    = 5 * m.SZR_TIME_UNIT_SECOND
    m.SZR_TIME_INTERVAL_OF_LONG_BUFFERING   = 7 * m.SZR_TIME_UNIT_MINUTE
    m.SZR_TIME_INTERVAL_OF_UPDATEING_IMAGE  = 5 * m.SZR_TIME_UNIT_MINUTE
    m.SZR_ERROR_CORRECTION_TIME         = 10 * m.SZR_TIME_UNIT_SECOND
    m.SZR_SESSION_TIME_OUT              = 30 * m.SZR_TIME_UNIT_MINUTE
    m.SZR_VIEWHOUR_GRACE_TIME           = 1500       

    m.SZR_DEFAULT_KEY                   = CreateObject("roString")
    m.SZR_PLUGIN_VERSION                = m.SZR_MESSAGE_SPEC_VERSION + "." + m.SZR_PLUGIN_REVISION_VERSION    
    m.SZR_USER_AGENT                    = "Streamlyzer ROKU plug-in " + m.SZR_PLUGIN_VERSION
    m.SZR_DECIMAL_POINT_MODIFIER        = 10000    


    'Set plugin Constnats
    m.SZR_CUSTOMER_KEY  = "ckey"
    
    'audience property
    m.SZR_USER_TYPE     = "utype"
    m.SZR_USER_ID       = "uid"
    m.SZR_GENDER        = "gen"          
    m.SZR_YEAR_OF_BIRTH = "yob"

    'service property
    m.SZR_SERVICE_TYPE  = "stype"
    m.SZR_SESSION_ID    = "sid"
    m.SZR_STREAMING_SERVER_NAME     = "svr"
    m.SZR_ABTEST_MARK   = "abtm"

    'content property
    m.SZR_LIVE            = "live"
    m.SZR_THUMBNAIL_IMAGE = "img"
    m.SZR_SERIES_NAME     = "srn"
    m.SZR_EPISODE_NAME    = "eps"
    m.SZR_LIVE_CHANNEL_NAME       = "chn"
    m.SZR_MOVIE_ID        = "mvid"
    m.SZR_MOVIE_CATEGORY  = "mvctg"
    m.SZR_MOVIE_SUBCATEGORY       = "mvsctg"
    m.SZR_MOVIE_CONTENTS_PROVIDER = "mvcp"
    m.SZR_MOVIE_RATE      = "mvrt"
    m.SZR_BIT_RATE        = "br"
    m.SZR_RESOLUTION      = "res"

    'platform property
    m.SZR_PLAYER_PLATFORM_VERSION = "ppv"
    m.SZR_MEDIA_PLAYER_VERSION    = "mpv"
    m.SZR_PLATFORM_NAME           = "pltn"
    m.SZR_APPLICATION_NAME        = "brn"
    m.SZR_APPLICATION_VERSION     = "brv"
    
    'Page Referral Keys
    m.SZR_KEY_REFERRER_HOSTNAME               = "rhost"
    m.SZR_KEY_REFERRER_PAGE_PATH              = "rpath"
    m.SZR_KEY_CURRENT_PAGE_PATH               = "cpath"

    'Shared Contents Tracking Keys
    m.SZR_KEY_SHARED_CONTENTS_TRACKING_DESTINATION    = "dst"    
        
    'Values
    m.SZR_VALUE_DEFAULT_UNSET   = "unset"
    
    m.SZR_VALUE_GENDER_FEMALE   = "f"    
    m.SZR_VALUE_GENDER_MALE     = "m"
    
    m.SZR_VALUE_LIVE_TRUE       = "t"
    m.SZR_VALUE_LIVE_FALSE      = "f"
    
    'observer types       
    m.SZR_OBS_TYPE_NONE                     = -1
    m.SZR_OBS_TYPE_PLAYBACK_EVENT           = 0
    m.SZR_OBS_TYPE_USER_DEFINED_EVENT       = 1
    m.SZR_OBS_TYPE_PAGE_REFERRER_EVENT      = 2
    m.SZR_OBS_TYPE_SHARED_CONTENTS_EVENT    = 3
    
    'buffering types    
    m.SZR_BUFFERING_TYPE_NORMAL = 0
    m.SZR_BUFFERING_TYPE_INIT = 1
    m.SZR_BUFFERING_TYPE_SEEK = 2
    m.SZR_BUFFERING_TYPE_LONG = 3
    
    m.szrPlaybackObservers = {}
           
End Function


Function SZRSetDebugMode(dbgMode as Boolean)
    GetGlobalAA().szrDebugMode = dbgMode
End Function


Function SZRCheckDebugMode() as Boolean
    if (GetGlobalAA().szrDebugMode = Invalid) then return false    
    return GetGlobalAA().szrDebugMode
End Function


Function SZRLogger(value as Object) 
    globals = GetGlobalAA()
    if (globals.szrDebugMode = Invalid)
    else    
        if (globals.szrDebugMode)
            print value
        end if
    end if
End Function


Function SZRPluginTimerUpdate() 

    globals = GetGlobalAA()
    if globals.szrTimer = Invalid 
        globals.szrTimer = CreateObject("roTimespan")
        globals.szrTimeout = 0
    end if
    
    if globals.szrTimer.TotalMilliseconds() > globals.szrTimeout 
        
        getSZRMessageHandlerInstance().processMessage()
        
        tmpDic = globals.szrPlaybackObservers
        for each obsKey in tmpDic
            tmpDic[obsKey].processMessage()            
        next      
                
        globals.szrTimer.Mark()
        globals.szrTimeout = globals.szrTimer.TotalMilliseconds() + 100                       
    end if        
    
End Function
