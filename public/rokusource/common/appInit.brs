Function LoadSettings(channel_id) As Dynamic

    http = NewHttp("http://1stud.io/tvapp/channel_" + channel_id + "/roku/settings.xml")
    Dbg("url: ", http.Http.GetUrl())

    rsp = http.GetToStringWithRetry()

    xml=CreateObject("roXMLElement")
    if not xml.Parse(rsp) then
         print "Can't parse feed"
        return invalid
    endif

    settings = xml.GetAttributes()
    anStr = strTokenize(settings.analytics, "|")
    settings.analytics = anStr[0]
    settings.analytics_customer_Key = anStr[1]
    settings.analytics_user_id = anStr[2]
    settings.analytics_server_name = anStr[3]

    settings.seedFile = settings.root_path + settings.seedfile_grid
    if (settings.active_layout="linear") then 
        settings.seedFile = settings.root_path + settings.seedfile_linear
    endif

    return settings
End Function

'******************************************************
'**  RokuChannelMaker Template 
'**  2014
'**  Copyright (c) 2014 RokuChannelMaker.com All Rights Reserved.
'******************************************************

Sub RokuAppEntryPoint(channel_id)
    facade=createobject("roimagecanvas")
    facade.show()

    'initialize theme attributes like titles, logos and overhang color
    settings = LoadSettings(channel_id)
    initTheme(settings)
    'print settings

    if (settings.appType="ustream") then
        init(settings.ustream_secret_code, settings.ustream_app_id)
        'init("cdb6e86bbac2ff3a62ea60bbbd3bc21a7f0c0001b494eb64050be2026f8f38b7", "com.baosn.roku") 'Ums.brs @ 30'
    end if

    'prepare the screen for display and get ready to begin
    screen=preShowHomeScreen("", "")
    if screen=invalid then
        print "unexpected error in preShowHomeScreen"
        return
    end if

    if (showInfo(settings)=1) then
        showHomeScreen(screen, settings)
    end if
End Sub

'*************************************************************
'** Set the configurable theme attributes for the application
'** 
'** Configure the custom overhang and Logo attributes
'** Theme attributes affect the branding of the application
'** and are artwork, colors and offsets specific to the app
'*************************************************************

Sub initTheme(settings)

    app = CreateObject("roAppManager")
    theme = CreateObject("roAssociativeArray")

    'theme.OverhangOffsetSD_X = "72"
    'theme.OverhangOffsetSD_Y = "48"
    'theme.OverhangSliceSD = "pkg:/images/ASY-Overhang-SD.jpg"
    'theme.OverhangLogoSD  = "pkg:/images/ASY-Overhang-SD1.jpg"
    'theme.BackgroundColor = "#b3ccff"
    'theme.OverhangOffsetHD_X = "128"
    'theme.OverhangOffsetHD_Y = "70"
    'theme.OverhangSliceHD = "pkg:/images/ASY-Overhang-HD.jpg"
    'theme.OverhangLogoHD  = "pkg:/images/ASY-Overhang-HD1.jpg"

    theme.OverhangOffsetSD_X = settings.theme_OverhangOffsetSD_X
    theme.OverhangOffsetSD_Y = settings.theme_OverhangOffsetSD_Y

    if (len(settings.theme_OverhangSliceSD) > 0) then
        theme.OverhangSliceSD =    settings.root_path + settings.theme_OverhangSliceSD
    endif

    if (len(settings.theme_OverhangLogoSD) > 0) then
        theme.OverhangLogoSD  =    settings.root_path + settings.theme_OverhangLogoSD
    endif

    theme.BackgroundColor =    settings.theme_BackgroundColor
    theme.OverhangOffsetHD_X = settings.theme_OverhangOffsetHD_X
    theme.OverhangOffsetHD_Y = settings.theme_OverhangOffsetHD_Y


    if (len(settings.theme_OverhangSliceHD) > 0) then
        theme.OverhangSliceHD =    settings.root_path + settings.theme_OverhangSliceHD
    endif

    if (len(settings.theme_OverhangLogoHD) > 0) then
        theme.OverhangLogoHD  =    settings.root_path + settings.theme_OverhangLogoHD
    endif

    print theme
    app.SetTheme(theme)
End Sub


