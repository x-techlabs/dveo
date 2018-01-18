'******************************************************
'**  RokuChannelMaker Template 
'**  2014
'**  Copyright (c) 2014 RokuChannelMaker.com All Rights Reserved.
'******************************************************

Function dialogIndicator(dialog, message = "Please wait...")
  ' set the text of the dialog
  dialog.SetTitle(message)
  dialog.ShowBusyAnimation()
  dialog.Show()
  return dialog  
End Function

'******************************************************
'** Perform any startup/initialization stuff prior to 
'** initially showing the screen.  
'******************************************************
Function preShowHomeScreen(breadA=invalid, breadB=invalid) As Object

    if validateParam(breadA, "roString", "preShowHomeScreen", true) = false return -1
    if validateParam(breadB, "roString", "preShowHomeScreen", true) = false return -1

    port=CreateObject("roMessagePort")
    screen = CreateObject("roPosterScreen")
    screen.SetMessagePort(port)
    
    if breadA<>invalid and breadB<>invalid then
        screen.SetBreadcrumbText(breadA, breadB)
    end if

    screen.SetListStyle("flat-category")
    screen.setAdDisplayMode("scale-to-fit")
    return screen

End Function

Function CreateContentList(shows As Object) As Object
    list = CreateObject("roArray", shows.count(), true)
    for i = 0 to shows.count() - 1
         o = CreateObject("roAssociativeArray")
         o.ContentType = "episode"
         o.Type = "episode"

         o.Title = shows[i].title '"[Title" + i.toStr() + "]"
         o.Description = ""
         o.ShortDescriptionLine1 = shows[i].title    '"[ShortDescriptionLine1]"
         o.ShortDescriptionLine2 = ""                '"[ShortDescriptionLine2]"
         o.Description =  shows[i].description       '"[Description] "
         o.Rating = shows[i].Rating
         o.StarRating = shows[i].StarRating
         o.ReleaseDate = ""
         o.Length = shows[i].Length
         o.Actors = []
         o.Director = ""
         o.viewing = shows[i].viewing
         o.subscription = shows[i].subscription
         
         o.HDPosterUrl = shows[i].hdImg
         o.SDPosterUrl = shows[i].sdImg
         o.StreamUrls = shows[i].StreamUrls
         
         list.Push(o)
     end for
     return list
End Function

'******************************************************
'** Display the home screen and wait for events from 
'** the screen. The screen will show retreiving while
'** we fetch and parse the feeds for the game posters
'******************************************************
Function showScreenGrid(kid As Object, settings As Object) As Integer

    screen = CreateObject("roAppManager")
    theme = CreateObject("roAssociativeArray")
    theme.GridScreenBackgroundColor=settings.theme_BackgroundColor  
    screen.SetTheme(theme)
 
    port=CreateObject("roMessagePort")
    grid = CreateObject("roGridScreen")
    grid.SetMessagePort(port)

	roDialog = CreateObject("roOneLineDialog")
	dialog = dialogIndicator(roDialog)

    if (kid.kids.count() = 0) then
        'print m.Categories.Kids[kidIndex%]
        data = getCategoryListFromURL(kid.feed) 
        kid.kids = data.kids
        kid.playlists_count = str( data.kids.count() )
    endif

    category = kid
    rowTitles = CreateObject("roArray", category.kids.count(), true)

    for ax = 0 to category.kids.count()-1
       cat = category.kids[ax]
       rowTitles.Push("[ " + cat.title + " ] ")
    end for

    'grid.SetGridStyle("two-row-flat-landscape-custom")
    grid.SetGridStyle("two-row-flat-landscape")
    grid.SetDisplayMode("scale-to-fill")
    grid.SetDescriptionVisible(false)

    grid.SetupLists(rowTitles.Count())
    grid.SetListNames(rowTitles)

    for j = 0 to category.kids.count()-1
        
        shows = getShowsForCategoryItem(category.kids[j])
        kid.kids[j].kids = shows
        grid.SetContentList(j, CreateContentList(shows))
     end for

     dialog.Close()
     grid.Show()

     while true
         msg = wait(0, port)
         if type(msg) = "roGridScreenEvent" then
             if msg.isScreenClosed() then
                 exit while
             else if msg.isListItemFocused()
                 print "Focused msg: ";msg.GetMessage();"row: ";msg.GetIndex();
                 print " col: ";msg.GetData()
                 
             else if msg.isListItemSelected()
                 r = msg.GetIndex()
                 c = msg.GetData()

                 print "Selected msg: ";msg.GetMessage();"row: ";r;" col: ";c
                 categ = kid.kids[r].kids[c]
                 print categ

                 displayVideo(categ, settings, "mp4")
             endif
         endif
     end while

    return 0

End Function

'**********************************************************
'** When a poster on the home screen is selected, we call
'** this function passing an associative array with the 
'** data for the selected show.  This data should be 
'** sufficient for the show detail (springboard) to display
'**********************************************************
Function displayCategoryPosterScreen(category As Object, settings As Object) As Dynamic

    if validateParam(category, "roAssociativeArray", "displayCategoryPosterScreen") = false return -1
    screen = preShowPosterScreen(category.Title, "")
    showPosterScreen(screen, category, settings)

    return 0
End Function

'**********************************************************
'** Special categories can be used to have categories that
'** don't correspond to the content hierarchy, but are
'** managed from the server by data from the feed.  In these
'** cases we might show a different type of screen other
'** than a poster screen of content. For example, a special
'** category could be search, music, options or similar.
'**********************************************************
Function displaySpecialCategoryScreen() As Dynamic

    ' do nothing, this is intended to just show how
    ' you might add a special category ionto the feed

    return 0
End Function

'************************************************************
'** initialize the category tree.  We fetch a category list
'** from the server, parse it into a hierarchy of nodes and
'** then use this to build the home screen and pass to child
'** screen in the heirarchy. Each node terminates at a list
'** of content for the sub-category describing individual videos
'************************************************************
Function getCategoryListFromURL(feed$) As Object

    if (Right(feed$, 4) = "mrss") then
        return invalid
    endif

    conn = InitCategoryFeedConnection(feed$)
    return conn.LoadCategoryFeed(conn)

End Function


Function initCategoryList(settings) As Void

    if (settings.appType = "ustream") then
        UStream_initCategoryList(settings)
        return
    endif

     conn = InitCategoryFeedConnection(settings.seedFile)
    m.Categories = conn.LoadCategoryFeed(conn)
    m.CategoryNames = conn.GetCategoryNames(m.Categories)

End Function

Function ShowParagraphScreen(cat as Object) As Void

    port = CreateObject("roMessagePort")
    screen = CreateObject("roParagraphScreen")
    screen.SetMessagePort(port) 

    screen.SetTitle(cat.title)
    'screen.AddHeaderText(cat.description)
    screen.AddParagraph(cat.description)

    screen.AddButton(1, "Back")
    'screen.AddButton(2, "[button text 2]")

    screen.Show() 

    while true

        msg = wait(0, screen.GetMessagePort()) 
        if type(msg) = "roParagraphScreenEvent"
            exit while 
        endif

    end while

End Function

function processKID(kid, settings)
    print "processKID"
    'print kid
    if (kid.layout="video") then
        displayVideoLive(kid, settings, "hls")
    else if (kid.layout="linear") then
        displayCategoryPosterScreen(kid, settings)
    else if (kid.layout="paragraph") then
        ShowParagraphScreen(kid)
    else if (kid.layout="grid") then
        showScreenGrid(kid, settings)
    endif
End Function

'******************************************************
'** Display the home screen and wait for events from 
'** the screen. The screen will show retreiving while
'** we fetch and parse the feeds for the game posters
'******************************************************
Function showHomeScreen(screen As Object, settings As Object) As Integer
    wPause% = 0
    if validateParam(screen, "roPosterScreen", "showHomeScreen") = false return -1
    if (settings.analytics="1") then
        initSZRPlugin()
        wPause% = 1
    end if
    initCategoryList(settings)
    index = 0

    screen.SetContentList(m.Categories.Kids)
    screen.SetFocusedListItem(index)
    screen.SetListStyle("arced-16x9")
    screen.Show()

    while true
        msg = wait(wPause%, screen.GetMessagePort())
        if type(msg) = "roPosterScreenEvent" then
            'print "showHomeScreen | msg = "; msg.GetMessage() " | index = "; msg.GetIndex()
            zxc = 5
            if msg.isListFocused() then
                'print "list focused | index = "; msg.GetIndex(); " | category = "; m.curCategory
                zxc = 5
            else if msg.isListItemSelected() then
                print "list item selected | index = "; msg.GetIndex()
                index = msg.GetIndex()
                kid = m.Categories.Kids[index]
                
                'print kid
                processKID(kid, settings)
            else if msg.isScreenClosed() then
                return -1
            end if
        end If

        if (settings.analytics="1") SZRPluginTimerUpdate()

    end while

    return 0

End Function
