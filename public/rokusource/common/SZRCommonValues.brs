
Function getSZRCommonValuesInstance(settings) as Object
    globals = GetGlobalAA()
    if globals.szrCommonValuesInstance = Invalid
        globals.szrCommonValuesInstance = newSZRCommonValuesInstance(settings).init()
    end if
    return globals.szrCommonValuesInstance    
End Function


Function newSZRCommonValuesInstance(settings) as Object
    
    obj = {}
    obj.settings = settings
    obj.basicProperties = {} 
    obj.contentsProperties = CreateObject("roArray", 0, true)
    obj.numContents = 0
        
    obj.init = Function() as Object
        globals = GetGlobalAA()

        m.basicProperties[globals.SZR_CUSTOMER_KEY]     = m.settings.analytics_customer_Key   ' "50e2fce54e7e4be693"   ' "Your_Customer_Key"

        m.basicProperties[globals.SZR_USER_ID]          = m.settings.analytics_user_id        ' "launch@onestudio.tv"  ' "streamlyzer_roku"
        m.basicProperties[globals.SZR_USER_TYPE]        = "free"
        m.basicProperties[globals.SZR_GENDER]           = globals.SZR_VALUE_GENDER_MALE
        m.basicProperties[globals.SZR_YEAR_OF_BIRTH]    = "1981"

        m.basicProperties[globals.SZR_STREAMING_SERVER_NAME]    = m.settings.analytics_server_name  ' "http://onestudio.tv/" ' "test_server"
        m.basicProperties[globals.SZR_SESSION_ID]               = "test_session_id"
        m.basicProperties[globals.SZR_ABTEST_MARK]              = "test_abtest"
        m.basicProperties[globals.SZR_SERVICE_TYPE]             = "OneStudio"

        m.basicProperties[globals.SZR_PLATFORM_NAME]                = "roku"
        m.basicProperties[globals.SZR_APPLICATION_NAME]             = "ASY"
        m.basicProperties[globals.SZR_APPLICATION_VERSION]          = "0.9.1.0"
        m.basicProperties[globals.SZR_PLAYER_PLATFORM_VERSION]      = "roku video player"
        m.basicProperties[globals.SZR_MEDIA_PLAYER_VERSION]         = "0.9.1.0"

        m.basicProperties[globals.SZR_LIVE]             = globals.SZR_VALUE_LIVE_TRUE
        m.basicProperties[globals.SZR_MOVIE_ID]         = "test movie id"
        m.basicProperties[globals.SZR_MOVIE_CATEGORY]   = "test movie category"
        m.basicProperties[globals.SZR_BIT_RATE]         = "0"
        m.basicProperties[globals.SZR_RESOLUTION]       = "320x240"
        m.basicProperties[globals.SZR_LIVE_CHANNEL_NAME]    = "test_channel"
                    
        'jsonAsString = ReadAsciiFile("pkg:/json/mediainfo.json")
        'json = ParseJSON(jsonAsString)

        'm.contentsProperties = json.MediaItems
        'm.numContents = m.contentsProperties.count()     
        
        return m   
    End Function    
        
    obj.contentsAtIndex = Function(idx as integer) as Object
        globals = GetGlobalAA()
                    
        ret = {} 
        ret[globals.SZR_LIVE] = m.contentsProperties[idx]["live"]
        ret[globals.SZR_THUMBNAIL_IMAGE] = m.contentsProperties[idx]["thumbnailImage"]        
        ret[globals.SZR_SERIES_NAME] = m.contentsProperties[idx]["seriesName"]
        ret[globals.SZR_EPISODE_NAME] = m.contentsProperties[idx]["episodeName"]        
        ret[globals.SZR_LIVE_CHANNEL_NAME] = m.contentsProperties[idx]["liveChannelName"]        
        ret[globals.SZR_MOVIE_ID] = m.contentsProperties[idx]["movieId"]
        ret[globals.SZR_MOVIE_CATEGORY] = m.contentsProperties[idx]["movieCategory"]
        ret[globals.SZR_MOVIE_SUBCATEGORY] = m.contentsProperties[idx]["movieSubcategory"]
        ret[globals.SZR_MOVIE_CONTENTS_PROVIDER] = m.contentsProperties[idx]["movieContentsProvider"]
        ret[globals.SZR_MOVIE_RATE] = m.contentsProperties[idx]["movieRate"]
        ret[globals.SZR_BIT_RATE] = m.contentsProperties[idx]["bitRate"]
        ret[globals.SZR_RESOLUTION] = m.contentsProperties[idx]["resolution"]
    
        return ret                       
    End Function
    
    obj.getBasicProperties = Function() as Object
        return m.basicProperties
    End Function
        
    obj.episodeToContentProperties = function(episode)     
        ret = {} 
        ret["live"] = ""
        ret["thumbnailImage"] = episode.hdposterurl        
        ret["seriesName"] = episode.title
        ret["episodeName"] = episode.title        
        ret["liveChannelName"] = episode.title        
        ret["movieId"] = episode.contentId
        ret["movieCategory"] = episode.genre
        ret["movieSubcategory"] = episode.genre
        ret["movieContentsProvider"] = episode.streamurls[0]
        ret["movieRate"] = episode.rating

        ret["bitRate"] = "0"
        if (episode.streambitrates.count() > 0) then
            ret["bitRate"] = episode.streambitrates[0]
        endif

        ret["resolution"] = "SD"
        if (episode.ishd = true) then
            ret["resolution"] = "HD"
        endif

        m.contentsProperties = CreateObject("roArray", 0, true)
        m.contentsProperties.push(ret)
        m.numContents = m.contentsProperties.count()     
    End Function

    return obj
    
End Function
