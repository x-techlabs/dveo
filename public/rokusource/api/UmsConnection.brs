'Contains the necessary key-value pair for the header
'
'@return nameValueMap The name of the header content
function getHttpHeader() as Object
    device = createObject("roDeviceInfo")
    nameValueMap = createObject("roAssociativeArray")
    model = createObject("roString")
    model.SetString("Roku " + device.GetModel() + " " + device.GetVersion())
    nameValueMap["User-Agent"] = model 
    nameValueMap["Referer"] = "http://www.ustream.tv"
    return nameValueMap    
end function

'Generating random number for the url
'
'@return umsRandomNumber random number string
function umsRandomGenerator() as String
    umsRandomNumber = "r"
    y = ""
    for i = 0 to 15
        y = Stri(RND(10))
        umsRandomNumber = umsRandomNumber + Right(y, 1) 
    end for
    return umsRandomNumber
end function

function newUmsConnection(application as String, streamId as String, password as String) as Object
	tUmsConnection = createObject("roAssociativeArray")
	tUmsConnection.application = application
	tUmsConnection.streamId = streamId
	tUmsConnection.password = password
	tUmsConnection.umsUrl = ""

	tUmsConnection.createUmsRequest = createUmsRequest

	return tUmsConnection
end function

'The stageurl consists of the url consist of 16 number, then one 1 number and channelId. Streaminfo contains the datas given by parameters.
'Concats stageurl and streamInfo on the umsUrl 
'
'@param appId 
'@param appversion 
'@param applicaton Stream can be channel or recorded video
'@param streamId The names of this channelId or recorded video Id.
'@param format The stream's format can be, for example, mp4 or hls
'@param password Password entered by the user.
'@return umsUrl The name of the url, which is necessary to the ums connection
function getUrl(appId as String, appVersion as String, application as String, streamId as String, format as String,password as String) as String
    deviceInfo = createObject("roDeviceInfo")
    deviceId = deviceInfo.GetDeviceUniqueId()
    request = createObject("roUrlTransfer")
    umsRandomNumber = umsRandomGenerator()

    if len(m.registeredModules["cluster"].host) > 0 then
        m.stageurl = m.registeredModules["cluster"].host
        m.registeredModules["cluster"] = newClusterRejectModule()
        m.registeredModules["connectionId"] = newConnectionIdModule()
    else if len(m.registeredModules["connectionId"].host) > 0 then
        m.stageurl = m.registeredModules["connectionId"].host
    else
        m.stageurl = umsRandomNumber +"-1-"+streamId+"-"+application+"-live.ums.ustream.tv"
    end if
           
    streamInfo = createobject("roAssociativeArray")    
    streamInfo["appId"] = appId
    streamInfo["application"] = application
    streamInfo["media"] = streamId
    streamInfo["appVersion"] = appVersion
    streamInfo["format"] = format
    streamInfo["rpin"] = deviceId
    streamInfo["password"] = password
    streamInfo["apiKeyVersion"] = "1.0"
    streamInfo["apiKey"] = m.apiKey
    streamInfo["applicationBundle"] = m.applicationBundleName
    streamInfo["referrer"] = m.applicationBundleName
    streamInfo["pageUrl"] = m.const.pageUrl
    if (len(m.registeredModules["connectionId"].connectionId) > 0) then
        streamInfo["connectionId"] = m.registeredModules["connectionId"].connectionId
    end if
    
    umsUrl = "http://"+m.stageurl+"/"+m.protocolVersion+"/ustream?"
        
    for each k in streamInfo 
        umsUrl = umsUrl + k
        umsUrl = umsUrl + "="
        umsUrl = umsUrl + streamInfo[k]
        umsUrl = umsUrl + "&"
   end for
   
   umsUrl = umsUrl.Left(umsUrl.Len()-1)
   return umsUrl
end function

'build the request object
'
'@return ums request object
function createUmsRequest() as Object
	request = CreateObject("roUrlTransfer")
    requestPort = CreateObject("roMessagePort")
    request.SetMessagePort(requestPort)
    request.SetHeaders(getHttpHeader())

    m.umsUrl = getUrl("606", "1", m.application, m.streamId, "json", m.password)
    'm.umsUrl = getUrl("com.baosn.roku", "1", m.application, m.streamId, "json", m.password)
    request.SetUrl(m.umsUrl)

    return request
end function

'do the request and return the response. this is needed to be at one place for logging purposes
'
'@param request The request object
'@return string The response
function doUmsRequest(request as Object) as String
	debugLogUmsRequest(request.getUrl(), "doUmsRequest")
	umsSimpletext = request.GetToString()
    print "ums response: ";umsSimpletext

    return umsSimpletext
end function

function debugLogUmsRequest(url as String, methodName as String)
	print "ums request from ";methodName
	print "ums url: ";url
	print "-----------------"
end function

function debugLogUmsRequestWithPost(url as String, methodName as String, params as String)
	print "ums request from ";methodName
	print "ums url: ";url
	print "post params: ";params
	print "-----------------"
end function
