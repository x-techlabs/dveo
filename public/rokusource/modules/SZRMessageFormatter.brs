
Function newSZRMessageFormatter() as Object
    
    obj = {}
                        
    obj.requiredProperties = {}
    obj.inputProperties = {}
    obj.allValuesChecked = false
    
    obj.prevChannelName = ""
    obj.ticketId = ""
    
    obj.obsType = GetGlobalAA().SZR_OBS_TYPE_NONE
    obj.liveCheck = false
        
    obj.init = Function() as Object
        return m
    End Function    

    
    obj.setObserverType = Function(obsType as integer)
        m.obsType = obsType
        m.requiredProperties = getSZRVariablesInstance().requiredProperties(obsType)
    End Function    
    
    
    obj.setProperties = Function(properties as Object)
        if (properties = Invalid)
            SZRLogger("Invalid Property!")
        else
        
            for each key in properties
                if (getSZRVariablesInstance().ACCEPTABLE_PROPERTIES[key] = Invalid)
                    SZRLogger("[" + key + "] is an invalid key.")
                else
                    if (m.requiredProperties.DoesExist(key))        
                        rVal = properties[key]                                   
                        if getSZRUtilInstance().isString(rVal) and getSZRUtilInstance().isEmptyStr(rVal)
                            properties.Delete(key)
                            SZRLogger("[" + getSZRVariablesInstance().ACCEPTABLE_PROPERTIES[key] + "] CANNOT be empty.")
                        else
                            m.requiredProperties[key] = true
                        end if
                    end if
                end if            
            next
            
            m.updateProperties(properties)
            m.allRequiredValueChecked()

        end if
    End Function


    obj.updateProperties = Function(properties as Object)
        globals = GetGlobalAA()    
        for each key in getSZRVariablesInstance().ACCEPTABLE_PROPERTIES
            if properties.DoesExist(key)
                if (key = globals.SZR_LIVE)
                    m.inputProperties[key] = globals.SZR_VALUE_LIVE_FALSE
                    if (getSZRUtilInstance().isBoolean(properties[key]))
                        if (properties[key])
                            m.inputProperties[key] = globals.SZR_VALUE_LIVE_TRUE
                        endif
                    else if (getSZRUtilInstance().isString(properties[key]))
                        r = CreateObject("roRegex", "^(1|T|t).*$", "")
                        if (r.IsMatch(properties[key]))
                            m.inputProperties[key] = globals.SZR_VALUE_LIVE_TRUE
                        end if                        
                    end if                    
                else if (key = globals.SZR_GENDER)
                    if (getSZRUtilInstance().isString(properties[key]))
                        rf = CreateObject("roRegex", "^(f|F).*$", "")
                        rm = CreateObject("roRegex", "^(m|M).*$", "")
                        
                        if (rf.IsMatch(properties[key]))
                            m.inputProperties[key] = globals.SZR_VALUE_GENDER_FEMALE
                        else if (rm.IsMatch(properties[key]))
                            m.inputProperties[key] = globals.SZR_VALUE_GENDER_MALE
                        end if
                    end if
                else if (key = globals.SZR_YEAR_OF_BIRTH)                
                    valStr = ""
                    if (getSZRUtilInstance().isInteger(properties[key]))
                        valStr = properties[key].toStr()
                    else if (getSZRUtilInstance().isString(properties[key]))
                        valStr = properties[key]
                    end if
                                        
                    r = CreateObject("roRegex", "^[1-9][0-9]{3}$", "")
                    if (r.IsMatch(valStr))
                        m.inputProperties[key] = valStr
                    end if
                else if (key = globals.SZR_BITRATE)                
                    valStr = ""
                    if (getSZRUtilInstance().isInteger(properties[key]))
                        valStr = properties[key].toStr()
                    else if (getSZRUtilInstance().isString(properties[key]))
                        valStr = properties[key]
                    end if
                    m.inputProperties[key] = valStr
                else
                    m.inputProperties[key] = properties[key]                    
                    if (globals.SZR_LIVE_CHANNEL_NAME = key)
                        m.prevChannelName = properties[key]
                    end if
                end if                
            end if
        next    
    End Function


    obj.allRequiredValueChecked = Function() as Boolean
    
        if (m.allValuesChecked) then return true
        rv = true
        
        if (SZRCheckDebugMode()) then 
            msg = "REQUIRED VALUES are missing. : "                        
            for each key in m.requiredProperties
                if m.requiredProperties[key] = false                
                    if (rv)
                        msg = msg + " "
                    else
                        msg = msg + ", " 
                    end if                    
                    msg = msg + getSZRVariablesInstance().ACCEPTABLE_PROPERTIES[key]
                    rv = false
                end if
            next
        
            if (rv = false)
                SZRLogger(msg)
            end if        
        else
            for each key in m.requiredProperties
                if m.requiredProperties[key] = false
                    rv = false
                    exit for
                end if                
            next        
        end if
    
        m.allValuesChecked = rv
        return rv
    
    End Function            
            
            
    obj.setTicketId = Function(ticketId as String)
        m.ticketId = ticketId
    End Function
        
        
    obj.isLiveCheck = Function() as Boolean
        return m.liveCheck
    End Function
    
    
    obj.makeBaseDictionary = Function(logType as String, szrTimeObjVal as Object) as Object
        globals = GetGlobalAA()
        
        event = {}
                            
        tmpObj = getSZRVariablesInstance().SYSTEM_PROPERTIES
        for each tmpKey in tmpObj
            event[tmpKey] = tmpObj[tmpKey]
        next
        
        event["type"]   = logType       
        
        event["ts"]     = getSZRTimeStr(szrTimeObjVal)
        event["ts2"]    = getSZRTimeStr2(szrTimeObjVal)
        event["szrid"]  = getSZRUtilInstance().getSZRID()
        event["ssid"]   = getSZRVariablesInstance().getSZRSessinId()
            
        tmpObj = getSZRVariablesInstance().DEFAULT_INPUT_PROPERTIES
        for each tmpKey in tmpObj
            if (m.inputProperties.DoesExist(tmpKey))
                event[tmpKey] = m.inputProperties[tmpKey]
            else
                event[tmpKey] = getSZRVariablesInstance().DEFAULT_INPUT_PROPERTIES[tmpKey]
            end if
        next
            
        if (m.obsType = globals.SZR_OBS_TYPE_PLAYBACK_EVENT)
            event["tid"] = m.ticketId
        end if
        
        if (m.obsType = globals.SZR_OBS_TYPE_PLAYBACK_EVENT) or (m.obsType = globals.SZR_OBS_TYPE_SHARED_CONTENTS_EVENT)
            tmpObj = getSZRVariablesInstance().DEFAULT_INPUT_PROPERTIES_MEDIA
            for each tmpKey in tmpObj
                if (m.inputProperties.DoesExist(tmpKey))
                    event[tmpKey] = m.inputProperties[tmpKey]
                else
                    event[tmpKey] = tmpObj[tmpKey]
                end if                
            next
        end if
        
        return event
    End Function                        
        

    '
    obj.getLoadFailureEvent = Function(szrTimeObjVal as Object, loadingTime as integer) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
        
        globals = GetGlobalAA()
        event = m.makeBaseDictionary("plyldfail", szrTimeObjVal)
        event["pldf"] = loadingTime.toStr()
            
        return event
    End Function
        
    obj.getPlayerReadyEvent = Function(szrTimeObjVal as Object, loadingTime as integer) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if

        globals = GetGlobalAA()
        event = m.makeBaseDictionary("plyRdy", szrTimeObjVal)
        event["tPld"] = loadingTime.toStr()

        event.delete(globals.SZR_BIT_RATE)
        event.delete(globals.SZR_RESOLUTION)

        return event
    End Function        
        
    obj.getErrorMessageEvent = Function(szrTimeObjVal as Object, errorMessage as String) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if

        globals = GetGlobalAA()
        event = m.makeBaseDictionary("errmsg", szrTimeObjVal)
        event["errmsg"] = errorMessage
        
        event.delete(globals.SZR_GENDER)
        event.delete(globals.SZR_BIT_RATE)
        event.delete(globals.SZR_RESOLUTION)
        event.delete(globals.SZR_THUMBNAIL_IMAGE)
        event.delete(globals.SZR_YEAR_OF_BIRTH)
                    
        return event
    End Function    
        
    obj.getBufferingEvent = Function(szrTimeObjVal as Object, bufferingType as integer, bufferingTime as integer, playerPosition as integer) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
        
        globals = GetGlobalAA()
        event = m.makeBaseDictionary(m.getBufferingKeyStr(bufferingType), szrTimeObjVal)
        event[m.getBufferingTimeKeyStr(bufferingType)] = bufferingTime.toStr() 
        
        event.delete(globals.SZR_THUMBNAIL_IMAGE)
        
        if (bufferingType = globals.SZR_BUFFERING_TYPE_INIT) 
            event.delete(globals.SZR_BIT_RATE)
            event.delete(globals.SZR_RESOLUTION)
        else
            event["spos"] = m.getPlayerPositionStr(playerPosition)
        end if

        return event
    End Function
    
    obj.getMediaPlayerEvent = Function(szrTimeObjVal as Object, mediaPlayerEvent as String, playerPosition as integer) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if

        globals = GetGlobalAA()
        event = m.makeBaseDictionary("mplyevent", szrTimeObjVal)
        event["mplyevnt"] = mediaPlayerEvent
        event["spos"] = m.getPlayerPositionStr(playerPosition)

        event.delete("devv")
        event.delete("devm")
        event.delete("osv")
        event.delete("osn")
        event.delete("brn")
        event.delete("brv")        
        event.delete(globals.SZR_THUMBNAIL_IMAGE)
        
        return event
    End Function        
        
    obj.getViewhourEvent = Function(szrTimeObjVal as Object, viewhour as integer, playerPosition as integer) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if

        globals = GetGlobalAA()
        event = m.makeBaseDictionary("vh", szrTimeObjVal)
        event["spos"] = m.getPlayerPositionStr(playerPosition)        
        event["drt"] = m.getDurationStr()
        event["tVH"] = viewhour.toStr()
        
        event.delete(globals.SZR_THUMBNAIL_IMAGE)
        
        return event
    End Function        
                       
    obj.getChannelChangeEvent = Function(szrTimeObjVal as Object) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if

        globals = GetGlobalAA()
        event = m.makeBaseDictionary("chchg", szrTimeObjVal)
        event["spos"] = m.getPlayerPositionStr(playerPosition)        
        event["pchn"] = m.prevChannelName
            
        event.delete("devv")
        event.delete("devm")
        event.delete("osv")
        event.delete("osn")
        event.delete("brn")
        event.delete("brv")
                    
        event.delete("brv")
        event.delete("brv")
        event.delete("brv")
        
        event.delete(globals.SZR_BIT_RATE)
        event.delete(globals.SZR_RESOLUTION)
        event.delete(globals.SZR_THUMBNAIL_IMAGE)
                
        return event
    End Function            
    
    obj.getBitrateChangeEvent = Function(szrTimeObjVal as Object, bitrate as integer) as Object    
        m.inputProperties[GetGlobalAA().SZR_BIT_RATE] = bitrate.toStr()
        return m.getMediaPlayerEvent(szrTimeObjVal, "brchg")
    End Function        
        
    obj.getPlayerPositionStr = Function(playerPosition as integer) as String
        ret = ""
        if (m.liveCheck)
            ret = ret + "L"
            dateobj = getSZRTimeObj().now()
            
            'hours
            tmpVal = dateobj.getHours()
            if (tmpVal < 10)
                ret = ret + "0"
            end if    
            ret = ret + tmpVal.toStr()
            
            'minutes
            tmpVal = dateobj.getMinutes()
            if (tmpVal < 10)
                ret = ret + "0"
            end if    
            ret = ret + tmpVal.toStr()            
        else            
            tmpMinuteValue = int(playerPosition / 60)
            for i=1 to 4
                ret = (tmpMinuteValue mod 10).toStr() + ret
                tmpMinuteValue = int(tmpMinuteValue / 10)    
            next            
            ret = "V" + ret
        end if          
        return ret  
    End Function 
    
    obj.getDurationStr = Function() as String
        return "0"
    End Function     
           
    obj.getBufferingKeyStr = Function(bufferingType as integer) as String
        globals = GetGlobalAA()
        if bufferingType = globals.SZR_BUFFERING_TYPE_INIT
            return "iBuf"
        else if bufferingType = globals.SZR_BUFFERING_TYPE_SEEK
            return "skBuf"
        else if bufferingType = globals.SZR_BUFFERING_TYPE_LONG
            return "lBuf"
        end if
        return "Buf"
    End Function

    obj.getBufferingTimeKeyStr = Function(bufferingType as integer) as String
        globals = GetGlobalAA()
        if bufferingType = globals.SZR_BUFFERING_TYPE_INIT
            return "tIBuf"
        else if bufferingType = globals.SZR_BUFFERING_TYPE_SEEK
            return "tSkBuf"
        else if bufferingType = globals.SZR_BUFFERING_TYPE_LONG
            return "tLBuf"
        end if
        return "tBuf"
    End Function
       
    '
    obj.getUserDefinedCountEvent = Function(szrTimeObjVal as Object, group as String, eventName as String) as Object    
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
            
        globals = GetGlobalAA()
        
        event = m.makeBaseDictionary("usrcntevt", szrTimeObjVal)
        
        event.delete(globals.SZR_MEDIA_PLAYER_VERSION)
        event.delete(globals.SZR_PLAYER_PLATFORM_VERSION)
        event.delete(globals.SZR_STREAMING_SERVER_NAME)
            
        event["evtgrp"] = group
        event["usrcntevtn"] = eventName
            
        return event            
    End Function
        
    obj.getUserDefinedSumEvent = Function(szrTimeObjVal as Object, group as String, eventName as String, sumValue as integer) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
        
        globals = GetGlobalAA()
        
        event = m.makeBaseDictionary("usrsumevt", szrTimeObjVal)

        event.delete(globals.SZR_MEDIA_PLAYER_VERSION)
        event.delete(globals.SZR_PLAYER_PLATFORM_VERSION)
        event.delete(globals.SZR_STREAMING_SERVER_NAME)
            
        event["evtgrp"] = group
        event["usrsumevtn"] = eventName
        event["usrsumevtv"] = sumValue.toStr()
        
        return event   
    End Function
        
    obj.getUserDefinedRevenuEvent = Function(szrTimeObjVal as Object, group as String, eventName as String, revValue as double) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
    
        globals = GetGlobalAA()
        
        event = m.makeBaseDictionary("usrrvnevt", szrTimeObjVal)

        event.delete(globals.SZR_MEDIA_PLAYER_VERSION)
        event.delete(globals.SZR_PLAYER_PLATFORM_VERSION)
        event.delete(globals.SZR_STREAMING_SERVER_NAME)
            
        event["evtgrp"] = group
        event["usrrvnevtn"] = eventName        
        event["usrrvnevtv"] = (int(revValue * globals.SZR_DECIMAL_POINT_MODIFIER)).toStr()
        
        return event   
    End Function        

    '
    obj.getPageReferrerEvent = Function(szrTimeObjVal as Object, pageReferrerProperties as Object) as Object    
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
    
        globals = GetGlobalAA()
        
        event = m.makeBaseDictionary("pgref", szrTimeObjVal)
    
        event.delete(globals.SZR_MEDIA_PLAYER_VERSION)
        event.delete(globals.SZR_SERVICE_TYPE)
        event.delete(globals.SZR_PLAYER_PLATFORM_VERSION)
        event.delete(globals.SZR_STREAMING_SERVER_NAME)
    
        event[globals.SZR_KEY_REFERRER_HOSTNAME]    = pageReferrerProperties[globals.SZR_KEY_REFERRER_HOSTNAME]
        event[globals.SZR_KEY_REFERRER_PAGE_PATH]   = pageReferrerProperties[globals.SZR_KEY_REFERRER_PAGE_PATH]
        event[globals.SZR_KEY_CURRENT_PAGE_PATH]    = pageReferrerProperties[globals.SZR_KEY_CURRENT_PAGE_PATH]

        return event
    End Function
        
    '
    obj.getSharedContentsEvent = Function(szrTimeObjVal as Object, group as String, destination as String) as Object
        if m.allRequiredValueChecked() = false
            return Invalid
        end if
    
        globals = GetGlobalAA()
        
        event = m.makeBaseDictionary("shr", szrTimeObjVal)
    
        event.delete(globals.SZR_BIT_RATE)
        event.delete(globals.SZR_RESOLUTION)
        event.delete(globals.SZR_THUMBNAIL_IMAGE)
    
        event["dst"] = destination
        event["evtgrp"] = group
        
        return event 
    End Function
    
    
    return obj
    
End Function
