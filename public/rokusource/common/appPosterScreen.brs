'******************************************************
'**  RokuChannelMaker Template 
'**  2014
'**  Copyright (c) 2014 RokuChannelMaker.com All Rights Reserved.
'******************************************************

'******************************************************
'** Perform any startup/initialization stuff prior to 
'** initially showing the screen.  
'******************************************************
Function preShowPosterScreen(breadA=invalid, breadB=invalid) As Object

    if validateParam(breadA, "roString", "preShowPosterScreen", true) = false return -1
    if validateParam(breadB, "roString", "preShowPosterScreen", true) = false return -1

    port=CreateObject("roMessagePort")
    screen = CreateObject("roPosterScreen")
    screen.SetMessagePort(port)
    
    screen.SetListStyle("arced-16x9")
    'screen.SetListStyle("arced-landscape")
    return screen

End Function


'******************************************************
'** Display the home screen and wait for events from 
'** the screen. The screen will show retreiving while
'** we fetch and parse the feeds for the game posters
'******************************************************
Function showPosterScreen(screen As Object, category As Object, settings As Object) As Integer

    print "In showPosterScreen..."
    if validateParam(screen, "roPosterScreen", "showPosterScreen") = false return -1
    if validateParam(category, "roAssociativeArray", "showPosterScreen") = false return -1

    'print category
    print "category.kids.count = ", category.kids.count(), category.feed

    catORfeed = "cat"
    if (category.kids.count() = 0) then
        if (settings.appType = "ustream") then
            category.kids = UStream_GetChannelVideos(category, settings)
        else
            data = getCategoryListFromURL(category.feed) 
            if (data = invalid) then 
                shows = getShowsForCategoryItem(category)
                catORfeed = "show"
                category.kids = shows
            else
                category.kids = data.kids
            endif
        endif
    endif

    m.curCategory = 0
    m.curShow     = 0
    wPause% = 0
    if (settings.analytics="1") wPause%=1

    screen.SetContentList(category.kids) 
    screen.Show()

    while true
        msg = wait(wPause%, screen.GetMessagePort())
        if type(msg) = "roPosterScreenEvent" then
            'print "showPosterScreen | msg = "; msg.GetMessage() " | index = "; msg.GetIndex()
            if msg.isListFocused() then
                m.curCategory = msg.GetIndex()
                m.curShow = 0
                screen.SetContentList( CreateContentList(category.kids[m.curCategory].kids) )
                print "list focused | current category = "; m.curCategory
            else if msg.isListItemSelected() then
                m.curShow = msg.GetIndex()
                'print "list item selected | current show = "; m.curShow

                cat = category.kids[m.curShow]
                'print category.kids[m.curShow]

                if (catORfeed = "cat") then
                    processKID(cat, settings)
                else 
                    if (cat.Lookup("feed")=invalid) then
                        m.curShow = showDetailScreen(category.kids, m.curShow, settings)
                    else
                        if (category.kids[m.curShow].feed.instr(".xml") > 0) then
                            displayCategoryPosterScreen(category.kids[m.curShow], settings)
                        else
                            m.curShow = showDetailScreen(category.kids, m.curShow, settings)
                        endif
                    endif
                endif

                screen.SetFocusedListItem(m.curShow)
                print "list item updated  | new show = "; m.curShow
            else if msg.isScreenClosed() then
                return -1
            end if
        end If

        if (settings.analytics="1") SZRPluginTimerUpdate()

    end while


End Function

'**************************************************************
'** Given an roAssociativeArray representing a category node
'** from the category feed tree, return an roArray containing 
'** the names of all of the sub categories in the list. 
'***************************************************************
Function getCategoryList(topCategory As Object) As Object

    if validateParam(topCategory, "roAssociativeArray", "getCategoryList") = false return -1

    if type(topCategory) <> "roAssociativeArray" then
        print "incorrect type passed to getCategoryList"
        return -1
    endif

    categoryList = CreateObject("roArray", 100, true)
    'categoryList.Push(topCategory)
    return categoryList

End Function

'********************************************************************
'** Return the list of shows corresponding the currently selected
'** category in the filter banner.  As the user highlights a
'** category on the top of the poster screen, the list of posters
'** displayed should be refreshed to corrrespond to the highlighted
'** item.  This function returns the list of shows for that category
'********************************************************************
Function getShowsForCategoryItem(category As Object) As Object

    if validateParam(category, "roAssociativeArray", "getCategoryList") = false return invalid 

    if (Right(category.feed, 4) = "mrss") then
		mrss = NWM_MRSS(category.feed)	' iniitialize a NWM_MRSS object
		episodes = mrss.GetEpisodes() 	' get all episodes found in the MRSS feed
        'print episodes
        return episodes
    endif

    conn = InitShowFeedConnection(category)
    showList = conn.LoadShowFeed(conn)
    return showList

End Function
