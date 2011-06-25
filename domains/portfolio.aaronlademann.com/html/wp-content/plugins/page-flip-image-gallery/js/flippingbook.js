/*
	FlippingBook Object
	0.5.10
*/

function FlippingBook(book_id)
{
	this.id = book_id;
	this.pages = [];
	this.enlargedImages = [];
	this.pageLinks = [];

	this.stageWidth = "100%";
	this.stageHeight = "480";

	this.settings = {
		bookWidth: 640,
		bookHeight: 480,
		pagesSet: this.pages,
		enlargedImagesSet: this.enlargedImages,
		pageLinksSet: this.pageLinks,
		scaleContent: true,
		preserveProportions: false,
		centerContent: true,
		hardcover: false,
		hardcoverThickness: 3,
		hardcoverEdgeColor: 0xFFFFFF,
		highlightHardcover: true,
		frameWidth: 0,
		frameColor: 0xFFFFFF,
		frameAlpha: 100,
		firstPageNumber: 1,
		autoFlipSize: 50,
		navigationFlipOffset: 30,
		flipOnClick: true,
		handOverCorner: true,
		handOverPage: true,
		alwaysOpened: false,
		staticShadowsType: "Symmetric", // Asymmetric, Symmetric, Default
		staticShadowsDepth: 1,
		staticShadowsLightColor: 0xFFFFFF, // works for "Symmetric" shadows only
		staticShadowsDarkColor: 0x000000,
		dynamicShadowsDepth: 1,
		dynamicShadowsLightColor: 0xFFFFFF, // works for "dark" pages only
		dynamicShadowsDarkColor: 0x000000,
		moveSpeed: 2,
		closeSpeed: 3,
		gotoSpeed: 3,
		rigidPageSpeed: 5,
		flipSound: "",
		hardcoverSound: "",
		preloaderType: "Progress Bar", // "Progress Bar", "Round", "Thin", "Dots", "Gradient Wheel", "Gear Wheel", "Line", "Animated Book", "None"
		pageBackgroundColor: 0x99CCFF,
		loadOnDemand: true,
		allowPagesUnload: false,
		showUnderlyingPages: false,
		playOnDemand: true,
		freezeOnFlip: false,
		darkPages: false,
		smoothPages: false,
		rigidPages: false,
		flipCornerStyle: "manually",// "first page only", "each page", "manually"
		flipCornerPosition: "top-right",// "bottom-right","top-right","bottom-left","top-left"
		flipCornerAmount: 70,
		flipCornerAngle: 45,
		flipCornerRelease: true,
		flipCornerVibrate: true,
		flipCornerPlaySound: false,
		fullscreenEnabled: true,
		zoomEnabled: true,
		zoomImageWidth: 900,
		zoomImageHeight: 1165,
		zoomOnClick: true,
		zoomUIColor: 0x8f9ea6,
		zoomHint: "Double click for zooming.",
		centerBook: true,
		useCustomCursors: false,
		dropShadowEnabled: true,
		dropShadowHideWhenFlipping: true,
		backgroundColor: 0xFFFFFF,
		backgroundImage: "",
		backgroundImagePlacement: "fit", //  "top left", "center", "fit"
		printEnabled: true,
		printTitle: "Print Pages",
		downloadURL: "",
		downloadTitle: "Download PDF",
		downloadSize: "Size: 4.7 Mb",
		downloadComplete: "Complete",
		navigationBarPlacement: "bottom", //  "top", "bottom"
		slideshowAutoPlay: false,
		slideshowDisplayDuration: 5000,
		goToPageField: true,
		slideshowButton: true,
		firstLastButtons: true,
		extXML: ""
	};

	this.containerId = "fbContainer-" + this.id;
};

FlippingBook.prototype.create = function(swfpath)
{
	this.settings.pagesSet = this.pages;
	this.settings.enlargedImagesSet = this.enlargedImages;
	this.settings.pageLinksSet = this.pageLinks;
	//this.addLoadEvent(this.onWindowLoad);
	swfobject.embedSWF(swfpath, this.containerId, this.stageWidth, this.stageHeight, "8.0.0", "js/expressInstall.swf", this.settings, {allowFullScreen: "true", allowScriptAccess: "always", bgcolor: "#" + this.settings.backgroundColor.toString(16), wmode: "opaque" });
};

FlippingBook.prototype.onPutPage = function(leftPageNumber, rightPageNumber)
{
	jQuery("#fb_leftPageDescription").slideUp("slow");
	if ((leftPageNumber != undefined) && (jQuery("#fb_page_" + leftPageNumber).html().length > 0)) {
		jQuery("#fb_leftPageDescription").html(jQuery("#fb_page_" + leftPageNumber).html());
		jQuery("#fb_leftPageDescription").slideDown("slow");
	}
	jQuery("#fb_rightPageDescription").slideUp("slow");
	if ((rightPageNumber != undefined) && (jQuery("#fb_page_" + rightPageNumber).html().length > 0)) {
		jQuery("#fb_rightPageDescription").html(jQuery("#fb_page_" + rightPageNumber).html());
		jQuery("#fb_rightPageDescription").slideDown("slow");
	}
};
