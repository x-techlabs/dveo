
Function getSZRUtilInstance() as Object
    globals = GetGlobalAA()
    if globals.szrUtilInstance = Invalid
        globals.szrUtilInstance = newSZRUtilInstance().init()
    end if
    return globals.szrUtilInstance    
End Function


Function getSZRTimeObj() as Object
    obj = {}
    
    obj.seconds = 0
    obj.milliseconds = 0
    
    obj.now = Function() as Object
        date = CreateObject("roDateTime")
        m.seconds = date.asSeconds()
        m.milliseconds = date.getMilliseconds()
        return m
    End Function
    
    obj.getDiff = Function(prevTime as Object) as Object
        ret = getSZRTimeObj()
        ret.milliseconds = (m.milliseconds - prevTime.milliseconds)
        ret.seconds = (m.seconds - prevTime.seconds)
        
        if (ret.milliseconds < 0)
            ret.milliseconds = ret.milliseconds + 1000
            ret.seconds = ret.seconds - 1  
        end if        
        return ret
    End Function    
    
    obj.isEmpty = Function() as Boolean
        return (m.seconds = 0) and (m.milliseconds = 0)
    End Function
    
    obj.timeValue = Function() as integer
        return (m.seconds * 1000 + m.milliseconds)
    End Function
    
    return obj
End Function

Function getSZRTimeStr(szrTimeObj as Object) as String
    return szrTimeObj.seconds.toStr() + szrTimeObj.milliseconds.toStr()        
End Function

Function getSZRTimeStrNow() as String
    return getSZRTimeStr(getSZRTimeObj().now())
End Function

Function getSZRTimeStr2(szrTimeObj as Object) as String
    if (szrTimeObj = Invalid)
        szrTimeObj = getSZRTimeObj().now()
    end if
    
    dateObj = CreateObject("roDateTime")
    dateObj.fromSeconds(szrTimeObj.seconds)

    'year
    ret = dateObj.getYear().toStr() + "-"
    
    'month
    tmpVal = dateObj.getMonth()
    if (tmpVal < 10)
        ret = ret + "0"
    end if    
    ret = ret + tmpVal.toStr() + "-"
    
    'day
    tmpVal = dateObj.getDayOfMonth()
    if (tmpVal < 10)
        ret = ret + "0"
    end if    
    ret = ret + tmpVal.toStr() + "T"

    'hours
    tmpVal = dateobj.getHours()
    if (tmpVal < 10)
        ret = ret + "0"
    end if    
    ret = ret + tmpVal.toStr() + ":"
    
    'minutes
    tmpVal = dateobj.getMinutes()
    if (tmpVal < 10)
        ret = ret + "0"
    end if    
    ret = ret + tmpVal.toStr() + ":"
    
    'seconds
    tmpVal = dateobj.getSeconds()
    if (tmpVal < 10)
        ret = ret + "0"
    end if    
    ret = ret + tmpVal.toStr() + "."

    'miliseconds
    tmpVal = szrTimeObj.milliseconds    
    if (tmpVal < 10)
        ret = ret + "00"
    else if (tmpVal < 100)
        ret = ret + "0"
    end if
    ret = ret + tmpVal.toStr() + "Z"

    return ret
End Function


Function newSZRUtilInstance() as Object
    
    obj = {}
    
    obj.di = CreateObject("roDeviceInfo")    
        
    obj.digest = CreateObject("roEVPDigest")
    obj.digest.Setup("sha1")
    
    
    obj.init = Function() as Object
        return m
    End Function    
        
     
    obj.getUUID = Function() as String       
        numStrs = "0123456789"        
        baseStr = getSZRTimeStrNow() + m.di.GetDeviceUniqueId()
        For i=0 to 16
            baseStr = baseStr + numStrs.Mid(Rnd(10) - 1, 1)              
        Next
        
        ba = CreateObject("roByteArray")
        ba.FromAsciiString(baseStr)
        
        baseStr = m.digest.process(ba)        
        uuidStr = baseStr.mid(0,8) + "-" + baseStr.mid(8,4) + "-" + baseStr.mid(12,4) + "-" + baseStr.mid(16,4) + "-" + baseStr.mid(20,12) 
                
        return uuidStr        
    end Function
    
    
    obj.getSZRID = Function() as String
        reg = CreateObject("roRegistry")
        sec = CreateObject("roRegistrySection", "SZRPluginData")
        if sec.Exists("SZRID")
            return sec.Read("SZRID")
        else        
            szrid = m.getUUID()
            sec.Write("SZRID",szrid)        
            return szrid
        end if
    end Function        
    
        
    obj.getLanguage = Function() as String
        return Left(m.di.GetCurrentLocale(), 2)
    End Function
    
    
    obj.getRegion = Function() as String
        return Right(m.di.GetCurrentLocale(), 2)
    End Function
    
    
    obj.getDeviceModel = Function() as String
        return m.di.GetModel()
    End Function
        
        
    obj.getDeviceVersion = Function() as String
        return m.di.GetVersion()
    End Function
        
        
    obj.isEmptyStr = Function(strValue as Dynamic) as Boolean
        if m.isString(strValue)      
            return (strValue.Trim().Len() = 0)
        end if
        return true
    End Function
    
    
    obj.isBoolean = Function(value As Dynamic) As Boolean
        Return value <> Invalid and (Type(value) = "roBoolean")
    End Function


    obj.isInteger = Function(value As Dynamic) As Boolean
        Return value <> Invalid and (Type(value) = "Integer" or Type(value) = "roInt" or Type(value) = "roInteger")
    End Function
    
    
    obj.isString = Function(value As Dynamic) As Boolean
        Return value <> Invalid and (Type(value) = "roString" or Type(value) = "String")
    End Function    
    
    
    obj.associativeArrayToJSON = Function(jsonArr as Object) as String
        jsonStr = ""
        for each key in jsonArr
            jsonStr = jsonStr + "," + Chr(34) + key + Chr(34) + ":"
            value = jsonArr[key]

            if type(value) <> "roString" and type(value) <> "String"
                value = value.toStr()
            end if
            jsonStr = jsonStr + Chr(34) + value + Chr(34)
        next                    
        
        jsonStr = "{" + Mid( jsonStr, 2, Len(jsonStr) - 1 ) + "}"        
        return jsonStr
    End Function
    
    
    obj.SimpleJSONAssociativeArray = Function( jsonArray as Object ) as String
        jsonString = "{"
    
        For Each key in jsonArray
            jsonString = jsonString + Chr(34) + key + Chr(34) + ":"
            value = jsonArray[ key ]
            If Type( value ) = "roString" Then
                jsonString = jsonString + Chr(34) + value + Chr(34)
            Else If Type( value ) = "roInt" Or Type( value ) = "roFloat" Then
                jsonString = jsonString + value.ToStr()
            Else If Type( value ) = "roBoolean" Then
                jsonString = jsonString + m.IIf( value, "true", "false" )
            Else If Type( value ) = "roArray" Then
                jsonString = jsonString + m.SimpleJSONArray( value )
            Else If Type( value ) = "roAssociativeArray" Then
                jsonString = jsonString + m.SimpleJSONAssociativeArray( value )
            End If
            jsonString = jsonString + ","
        Next
        
        If Right( jsonString, 1 ) = "," Then
            jsonString = Left( jsonString, Len( jsonString ) - 1 )
        End If
    
        jsonString = jsonString + "}"
        Return jsonString
    End Function
    
    
    obj.SimpleJSONArray = Function(jsonArray as Object) as String
        jsonString = "["
    
        For Each value in jsonArray
            If Type( value ) = "roString" Then
                jsonString = jsonString + Chr(34) + value + Chr(34)
            Else If Type( value ) = "roInt" Or Type( value ) = "roFloat" Then
                jsonString = jsonString + value.ToStr()
            Else If Type( value ) = "roBoolean" Then
                jsonString = jsonString + m.IIf( value, "true", "false" )
            Else If Type( value ) = "roArray" Then
                jsonString = jsonString + m.SimpleJSONArray( value )
            Else If Type( value ) = "roAssociativeArray" Then
                jsonString = jsonString + m.SimpleJSONAssociativeArray( value )
            End If
            jsonString = jsonString + ","
        Next
        
        If Right( jsonString, 1 ) = "," Then
            jsonString = Left( jsonString, Len( jsonString ) - 1 )
        End If
    
        jsonString = jsonString + "]"
        Return jsonString    
    end Function
    
    
    obj.IIF = Function ( Condition, Result1, Result2 )
        If Condition Then
            Return Result1
        Else
            Return Result2
        End If
    End Function    
        
    
    return obj
    
End Function
