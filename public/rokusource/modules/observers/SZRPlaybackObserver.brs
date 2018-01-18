Function newSZRPlaybackObserver() as Object
    
    obj = {}
    
    obj.obsid = getSZRUtilInstance().getUUID()
    obj.eventDispatcher = newSZREventDispatcher().init()

    obj.BUFFERING_TYPE_NORMAL = m.SZR_BUFFERING_TYPE_NORMAL
    obj.BUFFERING_TYPE_INIT = m.SZR_BUFFERING_TYPE_INIT
    obj.BUFFERING_TYPE_SEEK = m.SZR_BUFFERING_TYPE_SEEK
    obj.BUFFERING_TYPE_LONG = m.SZR_BUFFERING_TYPE_LONG

    obj.initBufferingCompletedFlag = false
    obj.firstPlayCompletedFlag = false
    obj.isPausedFlag = false
    obj.isBufferingFlag = false
    obj.isPlayingFlag = false    
    
    obj.bufferingType = 0
    obj.playerLoadBegin = getSZRTimeObj()
    obj.playBegin = getSZRTimeObj()
    obj.bufferingBegin = getSZRTimeObj()
    obj.playIdx = 0
    obj.playerCallbackPosIdx = 0
        
    obj.totalBuffering = 0
    obj.totalPlaying = 0 
            
    obj.init = Function()
        globals = GetGlobalAA()        
        globals.szrPlaybackObservers[m.obsid] = m
        m.eventDispatcher.setObserverType(globals.SZR_OBS_TYPE_PLAYBACK_EVENT)                
        m.resetVariables()
        return m
    End Function    
    
    
    obj.closeObserver = Function()
        globals = GetGlobalAA()    
        m.eventDispatcher.closeObserver()
        globals.szrPlaybackObservers.delete(m.obsid)
    End Function
    
    
    obj.setProperties = Function(properties as Object)
        m.eventDispatcher.setProperties(properties)
    End Function
    
        
    obj.processMessage = Function()
        globals = GetGlobalAA()
        if (m.isBufferingFlag)        
            ' buffering
            cTime = getSZRTimeObj().now()
            dTime = cTime.getDiff(m.bufferingBegin)
            dValue = dTime.timeValue()
            if (dValue >= globals.SZR_TIME_INTERVAL_OF_BUFFERING)                                  
                m.updateTotalBufferingTime(dValue)
                
                m.postMessage( m.eventDispatcher.formatter.getBufferingEvent(cTime, m.bufferingType, dValue, m.playerCallbackPosIdx) )
                SZRLogger("%%% Buffering Time has been sent : " + dValue.toStr())
                m.bufferingBegin = cTime
            end if
        else if (m.isPausedFlag)
            ' paused - do nothing
        else if (m.isPlayingFlag)
            ' playing
            cTime = getSZRTimeObj().now()
            dTime = cTime.getDiff(m.playBegin)
            dValue = dTime.timeValue()
                       
            if (dValue >= globals.SZR_TIME_INTERVAL_OF_VIEWHOUR)                                                          
                m.totalPlaying = m.totalPlaying + dValue
                
                m.postMessage( m.eventDispatcher.formatter.getViewhourEvent(cTime, dValue, m.playerCallbackPosIdx) )
                SZRLogger("%%% ViewHour Time has been sent : " + dValue.toStr())
                m.playBegin = cTime
                m.playIdx = m.playerCallbackPosIdx
            end if            
        end if               
    End Function
            
    
    obj.flushMessage = Function()    
        globals = GetGlobalAA()
        if (m.isBufferingFlag) 
            ' buffering
            cTime = getSZRTimeObj().now()
            dTime = cTime.getDiff(m.bufferingBegin)
            dValue = dTime.timeValue()
                        
            m.updateTotalBufferingTime(dValue)
            m.postMessage( m.eventDispatcher.formatter.getBufferingEvent(cTime, m.bufferingType, dValue, m.playerCallbackPosIdx) )
            SZRLogger("%%% Buffering Time has been sent : " + dValue.toStr())            
        else if (m.isPausedFlag)
            ' paused - do nothing
        else if (m.isPlayingFlag)
            ' playing
            cTime = getSZRTimeObj().now()
            dTime = cTime.getDiff(m.playBegin)
            dValue = dTime.timeValue()
            
            idxValue = (m.playerCallbackPosIdx - m.playIdx) * globals.SZR_TIME_UNIT_SECOND + globals.SZR_VIEWHOUR_GRACE_TIME
            if (dValue > idxValue)
                dValue = idxValue
                SZRLogger("###### Viewhour is calibrated with position index")
            end if  
            
            m.totalPlaying = m.totalPlaying + dValue                        
            m.postMessage( m.eventDispatcher.formatter.getViewhourEvent(cTime, dValue, m.playerCallbackPosIdx) )      
            SZRLogger("%%% ViewHour Time has been sent : " + dValue.toStr())      
        end if        
    End Function
        

    obj.resetVariables = Function()
        m.initBufferingCompletedFlag = false
        m.firstPlayCompletedFlag = false
        m.isPausedFlag = false
        m.isBufferingFlag = false    
        m.isPlayingFlag = false
        
        m.bufferingType = 0
        m.playBegin = getSZRTimeObj().now()
        m.bufferingBegin = getSZRTimeObj().now() 
        
        m.totalBuffering = 0
        m.totalPlaying = 0     
        m.playIdx = 0      
        
        m.eventDispatcher.formatter.setTicketId(getSZRUtilInstance().getUUID())
    End Function
    
    
    obj.sendPlayerEventSignal = Function(szrTimeObjVal as Object, playerEventStr as String) 
        m.postMessage( m.eventDispatcher.formatter.getMediaPlayerEvent(szrTimeObjVal, playerEventStr, m.playerCallbackPosIdx) )
    End Function         
    

    obj.updateTotalBufferingTime = Function(dValue as integer)
        if m.bufferingType = m.BUFFERING_TYPE_NORMAL
            m.totalBuffering = m.totalBuffering + dValue
            if (m.totalBuffering >= GetGlobalAA().SZR_TIME_INTERVAL_OF_LONG_BUFFERING)                        
                m.bufferingType = m.BUFFERING_TYPE_LONG
            end if             
        else if m.bufferingType = m.BUFFERING_TYPE_LONG
            m.totalBuffering = m.totalBuffering + dValue
        end if            
    End Function
    
    
    obj.updateVideoScreenEvent = Function(msg as Object)
        globals = GetGlobalAA()
                
        if msg.isStatusMessage()            'n/a
            ' do nothing
        else if msg.isPlaybackPosition()    'Playing
            m.playerCallbackPosIdx = msg.GetIndex()
            
            if (m.isPlayingFlag = false)            
                if (m.isBufferingFlag)
                    m.flushMessage()
                    m.isBufferingFlag = false                   
                end if                
                m.isPausedFlag = false                   
                m.isPlayingFlag = true
                m.playBegin = getSZRTimeObj().now()
                m.playIdx = m.playerCallbackPosIdx       
                
                if (m.firstPlayCompletedFlag)
                    m.sendPlayerEventSignal(m.playBegin, "play")
                    SZRLogger("### [STATE] PLAY")                
                else
                    m.firstPlayCompletedFlag = true
                    m.sendPlayerEventSignal(m.playBegin, "firstPlay")
                    SZRLogger("### [STATE] First PLAY")                
                end if         
            end if
        else if msg.isFullResult()          'Completed and Stop
            m.flushMessage()
            m.sendPlayerEventSignal(getSZRTimeObj().now(), "compl")                
            SZRLogger("### [STATE] COMPLETED")
            
            m.isBufferingFlag = false
            m.isPausedFlag = true
            m.isPlayingFlag = false            
                        
            m.sendPlayerEventSignal(getSZRTimeObj().now(), "stop")
            SZRLogger("### [STATE] STOP")
        else if msg.isPartialResult()       'STOP
            m.flushMessage()
            
            m.isBufferingFlag = false
            m.isPausedFlag = true
            m.isPlayingFlag = false
            
            m.sendPlayerEventSignal(getSZRTimeObj().now(), "stop")
            SZRLogger("### [STATE] STOP")
        else if msg.isRequestFailed()       'Fail Error
            m.onPlayerError(msg.GetMessage())
            SZRLogger("###  Message FAILED : " + msg.GetMessage())   
        else if msg.isPaused()
            if (m.isPausedFlag = false)
                m.flushMessage()        
               
                m.isBufferingFlag = false
                m.isPausedFlag = true
                m.isPlayingFlag = false
                
                m.sendPlayerEventSignal(getSZRTimeObj().now(), "pause")                
                SZRLogger("### [STATE] PAUSED")
            end if
        else if msg.isResumed()
            ' do nothing
        else if msg.isStreamStarted()       'Buffering
            if (m.isBufferingFlag = false)
                if m.initBufferingCompletedFlag
                    m.flushMessage()                    
                    if msg.getInfo().IsUnderrun
                        m.sendPlayerEventSignal(getSZRTimeObj().now(), "bufStart")
                        SZRLogger("### [STATE] REBUFFERING START")
                        if (m.totalBuffering > globals.SZR_TIME_INTERVAL_OF_LONG_BUFFERING)
                            m.bufferingType = m.BUFFERING_TYPE_LONG
                        else
                            m.bufferingType = m.BUFFERING_TYPE_NORMAL
                        end if
                    else
                        m.sendPlayerEventSignal(getSZRTimeObj().now(), "skBufStart")
                        SZRLogger("### [STATE] SEEK BUFFERING START")
                        m.bufferingType = m.BUFFERING_TYPE_SEEK
                    end if
                else
                    m.onPlayerReady()
                    SZRLogger("### [STATE] PLAYER READY")
                
                    m.sendPlayerEventSignal(getSZRTimeObj().now(), "iBufStart")
                    SZRLogger("### [STATE] INIT BUFFERING START")
                    m.initBufferingCompletedFlag = true
                    m.bufferingType = m.BUFFERING_TYPE_INIT
                end if

                m.isBufferingFlag = true          
                m.isPlayingFlag = false
                m.bufferingBegin = getSZRTimeObj().now()
            end if
        end if                  
    End Function
    
    
    obj.postMessage = Function(msg as Object)
        getSZRMessageHandlerInstance().addMessage( msg )
    End Function
    
    
    obj.onPlayerLoad = Function()
        m.resetVariables()
        SZRLogger("### [STATE] PLAYER LOADING START")
        m.playerLoadBegin = getSZRTimeObj().now()
    End Function
    

    obj.onPlayerReady = Function()
        if (m.playerLoadBegin.isEmpty() = false)
            cTime = getSZRTimeObj().now()
            dTime = cTime.getDiff(m.playerLoadBegin)
            dValue = dTime.timeValue()
            
            m.postMessage( m.eventDispatcher.formatter.getPlayerReadyEvent(getSZRTimeObj().now(), dValue) )
            m.playerLoadBegin = getSZRTimeObj() 
        end if        
    End Function      
    
    
    obj.onPlayerLoadFail = Function()
        if (m.playerLoadBegin.isEmpty() = false)
            cTime = getSZRTimeObj().now()
            dTime = cTime.getDiff(m.playerLoadBegin)
            dValue = dTime.timeValue()
        
            m.postMessage( m.eventDispatcher.formatter.getLoadFailureEvent(getSZRTimeObj().now(), dValue) )
            m.playerLoadBegin = getSZRTimeObj()
        end if
    End Function      
 

    obj.onChannelChange = Function()
        m.postMessage( m.eventDispatcher.formatter.getChannelChangeEvent(getSZRTimeObj().now()) )
    End Function

    
    obj.onBitrateChange = Function(bitrate as integer)
        m.postMessage( m.eventDispatcher.formatter.getBitrateChangeEvent(getSZRTimeObj().now(), bitrate) )
    End Function
                       
    
    obj.onPlayerError = Function(errorMsg as String)
        m.postMessage( m.eventDispatcher.formatter.getErrorMessageEvent(getSZRTimeObj().now(), errorMsg) )
    End Function

        
    return obj
    
End Function
