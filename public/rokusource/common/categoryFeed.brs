'******************************************************
'**  RokuChannelMaker Template 
'**  2014
'**  Copyright (c) 2014 RokuChannelMaker.com All Rights Reserved.
'******************************************************

'******************************************************
' Set up the category feed connection object
' This feed provides details about top level categories 
'******************************************************
Function InitCategoryFeedConnection(feed$) As Object

    conn = CreateObject("roAssociativeArray")
    
    conn.UrlPrefix = "1stud.io/tvapp/channel_35/roku/xml"
    'conn.UrlPrefix = "rokuchannelmaker.com/udemy/xml-files"
    conn.UrlCategoryFeed = conn.UrlPrefix + "/categories.xml"

    if (len(feed$) > 0) then
        conn.UrlCategoryFeed = feed$
    endif

    conn.Timer = CreateObject("roTimespan")

    conn.LoadCategoryFeed    = load_category_feed
    conn.GetCategoryNames    = get_category_names

    print "created feed connection for " + conn.UrlCategoryFeed
    return conn

End Function

'*********************************************************
'** Create an array of names representing the children
'** for the current list of categories. This is useful
'** for filling in the filter banner with the names of
'** all the categories at the next level in the hierarchy
'*********************************************************
Function get_category_names(categories As Object) As Dynamic

    categoryNames = CreateObject("roArray", 100, true)

    for each category in categories.kids
        'print category.Title
        categoryNames.Push(category.Title)
    next

    return categoryNames

End Function


'******************************************************************
'** Given a connection object for a category feed, fetch,
'** parse and build the tree for the feed.  the results are
'** stored hierarchically with parent/child relationships
'** with a single default node named Root at the root of the tree
'******************************************************************
Function load_category_feed(conn As Object) As Dynamic

    http = NewHttp(conn.UrlCategoryFeed)

    Dbg("url: ", http.Http.GetUrl())

    m.Timer.Mark()
    if (conn.UrlCategoryFeed.instr("pkg:") > -1) then
        rsp = ReadAsciiFile(conn.UrlCategoryFeed)
    else
        rsp = http.GetToStringWithRetry()
    endif
    Dbg("Took: ", m.Timer)

    m.Timer.Mark()
    xml=CreateObject("roXMLElement")
    if not xml.Parse(rsp) then
         print "Can't parse feed"
        return invalid
    endif
    Dbg("Parse Took: ", m.Timer)

    m.Timer.Mark()
    if xml.category = invalid then
        print "no categories tag"
        return invalid
    endif

    if islist(xml.category) = false then
        print "invalid categories body"
        return invalid
    endif

    if xml.category.count() = 0 then
        print "invalid categories body"
        return invalid
    endif

    if xml.category[0].GetName() <> "category" then
        print "no initial category tag"
        return invalid
    endif

    topNode = MakeEmptyCatNode()
    topNode.Title = "root"
    topNode.isapphome = true

    'print "begin category node parsing"

    categories = xml.GetChildElements()
    'print "number of categories: " + itostr(categories.Count())
    for each e in categories 
        o = ParseCategoryNode(e)
        if o <> invalid then
            topNode.AddKid(o)
            'print "added new child node"
        else
            print "parse returned no child node"
        endif
    next
    Dbg("Traversing: ", m.Timer)

    return topNode

End Function

'******************************************************
'MakeEmptyCatNode - use to create top node in the tree
'******************************************************
Function MakeEmptyCatNode() As Object
    return init_category_item()
End Function


'***********************************************************
'Given the xml element to an <Category> tag in the category
'feed, walk it and return the top level node to its tree
'***********************************************************
Function UnEscapeSpecialCharacters(txt) As String
    'print type(txt)
    if (type(txt) <> "roString") return ""

    txt1$ = strReplace(txt, "&eq", "=")
    return txt1$
End Function

Function ParseCategoryNode(xml As Object) As dynamic
    o = init_category_item()

    'print "ParseCategoryNode: " + xml.GetName()
    'PrintXML(xml, 5)

    'parse the curent node to determine the type. everything except
    'special categories are considered normal, others have unique types 
    if xml.GetName() = "category" then
        'print "category: " + xml@title + " | " + xml@description 
        o.Type = "normal"
        o.Title = xml@title
        o.contentid = xml@contentId
        o.contenttype = "1"
        o.Description = xml@Description
        o.ShortDescriptionLine1 = xml@Title
        o.ShortDescriptionLine2 = xml@Description
        o.Description = xml@Description
        o.SDPosterURL = xml@sd_img
        o.HDPosterURL = xml@hd_img
        o.viewing = xml@viewing
        o.subscription = xml@subscription

        o.Feed = UnEscapeSpecialCharacters(xml@feed)
        o.StreamUrl = UnEscapeSpecialCharacters(xml@stream_url)
        o.playlists_count = xml@playlists_count
        o.layout = xml@layout
        'print "layout=",xml@layout

        if (o.layout = invalid) then
            o.layout = "linear"
        endif
        

    else if xml.GetName() = "categoryLeaf" then
        o.Type = "normal"
    else if xml.GetName() = "specialCategory" then
        if invalid <> xml.GetAttributes() then
            for each a in xml.GetAttributes()
                if a = "type" then
                    o.Type = xml.GetAttributes()[a]
                    print "specialCategory: " + xml@type + "|" + xml@title + " | " + xml@description
                    o.Title = xml@title
                    o.Description = xml@Description
                    o.ShortDescriptionLine1 = xml@Title
                    o.ShortDescriptionLine2 = xml@Description
                    o.SDPosterURL = xml@sd_img
                    o.HDPosterURL = xml@hd_img
                    o.Feed = xml@feed
                endif
            next
        endif
    else
        print "ParseCategoryNode skip: " + xml.GetName()
        return invalid
    endif

    'only continue processing if we are dealing with a known type
    'if new types are supported, make sure to add them to the list
    'and parse them correctly further downstream in the parser 
    while true
        if o.Type = "normal" exit while
        if o.Type = "special_category" exit while
        print "ParseCategoryNode unrecognized feed type"
        return invalid
    end while 
    return o
End Function


'******************************************************
'Initialize a Category Item
'******************************************************
Function init_category_item() As Object
    o = CreateObject("roAssociativeArray")
    o.Title       = ""
    o.contentid   = "0"
    o.contenttype = "1"
    o.Type        = "normal"
    o.Description = ""
    o.Kids        = CreateObject("roArray", 100, true)
    o.Parent      = invalid
    o.Feed        = ""
    o.IsLeaf      = cn_is_leaf
    o.AddKid      = cn_add_kid
    o.Feed        = ""
    o.StreamUrl   = "" 
    return o
End Function


'********************************************************
'** Helper function for each node, returns true/false
'** indicating that this node is a leaf node in the tree
'********************************************************
Function cn_is_leaf() As Boolean
    if m.Kids.Count() > 0 return true
    if m.Feed <> "" return false
    return true
End Function


'*********************************************************
'** Helper function for each node in the tree to add a 
'** new node as a child to this node.
'*********************************************************
Sub cn_add_kid(kid As Object)
    if kid = invalid then
        print "skipping: attempt to add invalid kid failed"
        return
     endif
    
    kid.Parent = m
    m.Kids.Push(kid)
End Sub


