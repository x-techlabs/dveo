Function UStream_GetChannelLiveStream(category As Object) As Object
    item = init_show_feed_item()

    item.ContentId        = "0" 
    item.Title            = category.Title
    item.Description      = category.description 
    item.hdImg            = category.HDPosterURL
    item.sdImg            = category.SDPosterURL
    item.Runtime          = ""

    item.ContentType      = "live"
    item.ContentQuality   = ""
    item.Synopsis         = ""
    item.Genre            = ""
    item.HDBifUrl         = ""
    item.SDBifUrl         = ""
    item.StreamFormat     = ""
    item.StarRating       = ""
    item.Rating           = ""
    
    item.ShortDescriptionLine1 = category.ShortDescriptionLine1 
    item.ShortDescriptionLine2 = category.ShortDescriptionLine1
    item.HDPosterUrl           = item.hdImg
    item.SDPosterUrl           = item.sdImg
    item.Length = 0
    item.StreamUrls.Push(category.StreamUrl)

    return item
End Function

Function UStream_GetChannelVideos(category As Object, settings As Object) As Object

    url = settings.root_path + "/xml/" + category.Id + "_videos.xml"
    http = NewHttp(url)
    rsp = http.GetToStringWithRetry()
    'print url
    'print rsp

    xml=CreateObject("roXMLElement")
    if not xml.Parse(rsp) then
        print "Invalid XML Format"
        return invalid
    endif

    if xml.videos = invalid then
        print "no videos tag"
        return invalid
    endif

    if islist(xml.videos) = false then
        print "invalid videos feed body"
        return invalid
    endif

    if xml.videos.count() = 0 then
        print "invalid videos feed body"
        return invalid
    endif
    vidArray = xml.videos[0].array

    kids = createObject("roArray", 1, true)    

    if (category.StreamUrl <> "") then
        kids.Push(UStream_GetChannelLiveStream(category))
    endif

    for t = 0 to vidArray.count()-1

        item = init_show_feed_item()
        curShow = vidArray[t]

        item.ContentId        = validstr(curShow.id.GetText()) 
        item.Title            = validstr(curShow.title.GetText()) 
        item.Description      = ""   ' validstr(curShow.description.GetText()) 
        item.hdImg            = validstr(curShow.thumbnail[0].default.getText()) 
        item.sdImg            = validstr(curShow.thumbnail[0].default.getText()) 
        item.Runtime          = validstr(curShow.length.GetText())

        item.ContentType      = "recorded"
        item.ContentQuality   = ""
        item.Synopsis         = ""
        item.Genre            = ""
        item.HDBifUrl         = ""
        item.SDBifUrl         = ""
        item.StreamFormat     = ""
        if item.StreamFormat = "" then  'set default streamFormat to mp4 if doesn't exist in xml
            item.StreamFormat = "mp4"
        endif

        item.StarRating    = ""
        item.Rating        = ""
        
        'map xml attributes into screen specific variables
        item.ShortDescriptionLine1 = item.Title 
        item.ShortDescriptionLine2 = item.Description
        item.HDPosterUrl           = item.hdImg
        item.SDPosterUrl           = item.sdImg

        item.Length = strtoi(item.Runtime)
        item.Categories = CreateObject("roArray", 5, true)
        item.Actors = CreateObject("roArray", 5, true)
        item.Description = item.Synopsis
        
        'Set Default screen values for items not in feed
        item.HDBranded = false
        item.IsHD = false
        item.StarRating = "90"
        item.ContentType = "episode" 

        mu = curShow.media_urls
        'media may be at multiple bitrates, so parse an build arrays
        for idx = 0 to mu.count()-1
            e = mu[idx]
            if e  <> invalid then
                item.StreamBitrates.Push("1000")
                item.StreamQualities.Push("SD")
                item.StreamUrls.Push(validstr(e.flv.GetText()))
            endif
        next idx
        
        kids.Push(item)
    next
    return kids
End Function

Function UStream_GetChannelInfo(channelID$, settings As Object)

    url = settings.root_path + "/xml/" + channelID$ + ".xml"
    http = NewHttp(url)
    rsp = http.GetToStringWithRetry()

    'print url
    'print rsp

    xml=CreateObject("roXMLElement")
    if not xml.Parse(rsp) then
        print "Invalid XML Format"
        return invalid
    endif

    if xml.channel = invalid then
        print "no channels tag"
        return invalid
    endif

    if islist(xml.channel) = false then
        print "invalid feed body"
        return invalid
    endif

    if xml.channel.count() = 0 then
        print "invalid feed body"
        return invalid
    endif
    
    o = init_category_item()
    o.Id = xml.channel[0].id.getText()
    o.Type = "normal"
    o.Title = xml.channel[0].title.getText()
    o.Description = xml.channel[0].Description.getText()
    o.ShortDescriptionLine1 = xml.channel[0].Title.getText()
    o.ShortDescriptionLine2 = xml.channel[0].Description.getText()
    o.Description = xml.channel[0].Description.getText()

    o.SDPosterURL = xml.channel[0].thumbnail[0].live.getText()
    o.HDPosterURL = o.SDPosterURL
    o.Feed = ""
    o.StreamUrl = ""
    o.playlists_count = ""
    o.layout = "linear"
    o.kids = []

    st = xml.channel[0].stream
    if (st <> invalid) then
        if (st.count() > 0) then
            o.StreamUrl = st[0].hls.getText()
        endif

    endif

    'o.kids = UStream_GetChannelVideos(o.Id)
    return o
End Function


Function UStream_initCategoryList(settings As Object) As void

    feedList = settings.channel_ids
    topNode = MakeEmptyCatNode()
    topNode.Title = "root"
    topNode.isapphome = true

    for i = 0 to feedList.count()-1
        o = UStream_GetChannelInfo(feedList[i], settings)
        if o <> invalid then
            topNode.AddKid(o)
        else
            print "parse returned no child node"
        endif
    next

    m.Categories = topNode

    categoryNames = CreateObject("roArray", 100, true)
    for each category in topNode.kids
        'print category.Title
        categoryNames.Push(category.Title)
    next
    m.CategoryNames = categoryNames
End Function
