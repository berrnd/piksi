Piksi.Api = {};
Piksi.Api.Post = function(apiFunction, jsonData, success, error)
{
	var xhr = new XMLHttpRequest();
	var url = U("/api/" + apiFunction);

	xhr.onreadystatechange = function()
	{
		if (xhr.readyState === XMLHttpRequest.DONE)
		{
			if (xhr.status === 200 || xhr.status === 204)
			{
				if (success)
				{
					if (xhr.status === 200)
					{
						success(JSON.parse(xhr.responseText));
					}
					else
					{
						success({});
					}
				}
			}
			else
			{
				if (error)
				{
					error(xhr);
				}
			}
		}
	};

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json");
	xhr.send(JSON.stringify(jsonData));
};

U = function(relativePath)
{
	return Piksi.BaseUrl.replace(/\/$/, "") + relativePath;
}

Piksi.Translator = new window.translator.default(Piksi.LocalizationStrings);
__t = function(text, ...placeholderValues)
{
	if (Piksi.Mode === "dev")
	{
		var text2 = text;
		if (Piksi.LocalizationStrings && !Piksi.LocalizationStrings.messages[""].hasOwnProperty(text2))
		{
			Piksi.Api.Post("system/log-missing-localization", { "text": text2 });
		}
	}

	return Piksi.Translator.__(text, ...placeholderValues)
}
__n = function(number, singularForm, pluralForm)
{
	if (Piksi.Mode === "dev")
	{
		var singularForm2 = singularForm;
		if (Piksi.LocalizationStrings && !Piksi.LocalizationStrings.messages[""].hasOwnProperty(singularForm2))
		{
			Piksi.Api.Post("system/log-missing-localization", { "text": singularForm2 });
		}
	}

	if (!pluralForm)
	{
		pluralForm = singularForm;
	}

	return Piksi.Translator.n__(singularForm, pluralForm, Math.abs(number), Math.abs(number))
}

ResizeResponsiveEmbeds = function()
{
	$("iframe.embed-responsive").each(function()
	{
		$(this).attr("height", $(this)[0].contentWindow.document.body.scrollHeight.toString() + "px");
	});
}
$(window).on("resize", function()
{
	ResizeResponsiveEmbeds();
});
$("iframe").on("load", function()
{
	ResizeResponsiveEmbeds();
});

$(document).on("click", ".show-as-dialog-link", function(e)
{
	e.preventDefault();

	var link = $(e.currentTarget).attr("href");

	bootbox.dialog({
		"message": '<iframe class="embed-responsive" src="' + link + '"></iframe>',
		"size": "large",
		"buttons": {
			"cancel": {
				"label": __t("Close"),
				"className": "btn-secondary",
				"callback": function()
				{
					bootbox.hideAll();
				}
			}
		}
	});
});

$(document).on("click", ".show-as-dialog-image", function(e)
{
	e.preventDefault();

	var dialogHtml = '\
		<div class="d-grid"> \
			<button class="btn btn-light mb-2 close-bootbox"> \
				<i class="fa-solid fa-left-long"></i> ' + __t('Back') + ' \
			</button> \
		</div> \
		<img class="img-fluid close-bootbox" src="' + $(e.currentTarget).attr("href") + '"></img> \
		<div class="row mt-2"> \
			<div class="col"> \
				<button class="btn btn-light w-100 rotate-image-button" data-rotation-factor="-90"> \
					<i class="fa-solid fa-rotate-left"></i> ' + __t('Rotate image to the left') + ' \
				</button> \
			</div> \
			<div class="col"> \
				<button class="btn btn-light w-100 rotate-image-button" data-rotation-factor="90"> \
					<i class="fa-solid fa-rotate-right"></i> ' + __t('Rotate image to the right') + ' \
				</button> \
			</div> \
		</div>';

	bootbox.dialog({
		"message": dialogHtml,
		"size": "extra-large"
	}).find(".modal-dialog").addClass("media-modal").find(".modal-content").addClass("bg-secondary");
});

$(document).on("click", ".show-as-dialog-video", function(e)
{
	e.preventDefault();

	var dialogHtml = '\
		<div class="d-grid"> \
			<button class="btn btn-light mb-2 close-bootbox"> \
				<i class="fa-solid fa-left-long"></i> ' + __t('Back') + ' \
			</button> \
		</div> \
			<video controls autoplay class="img-fluid"> \
				<source src="' + $(e.currentTarget).attr("href") + '"> \
		</div>';

	bootbox.dialog({
		"message": dialogHtml,
		"size": "extra-large"
	}).find(".modal-dialog").addClass("media-modal").find(".modal-content").addClass("bg-secondary");
});

$(document).on("click", ".close-bootbox", function(e)
{
	e.preventDefault();

	bootbox.hideAll();
});

$(document).on("shown.bs.modal", function(e)
{
	ResizeResponsiveEmbeds();
})

RefreshContextualTimeago = function(rootSelector = "#page-content")
{
	$(rootSelector + " time.timeago").each(function()
	{
		var element = $(this);
		if (!element.hasAttr("datetime"))
		{
			element.text("")
			return
		}

		var timestamp = element.attr("datetime");
		if (timestamp.isEmpty() || timestamp.length < 10)
		{
			element.text("")
			return
		}

		if (!moment(timestamp).isValid())
		{
			element.text("")
			return
		}

		var isToday = timestamp && timestamp.substring(0, 10) == moment().format("YYYY-MM-DD");
		if (isToday)
		{
			element.text(__t("Today"));
		}
		else
		{
			element.text(moment(timestamp).fromNow());
		}

		var isDateWithoutTime = element.hasClass("timeago-date-only");
		if (isDateWithoutTime)
		{
			element.prev().text(element.prev().text().substring(0, 10));
		}
	});
}
RefreshContextualTimeago();

$(document).on("click", ".easy-copy-textbox", function()
{
	$(this).select();
});

bootbox.setDefaults({
	"backdrop": true,
	"closeButton": false,
	"centerVertical": true
})

RotateImage = function(img, rotationFactor)
{
	var currentRotation = 0
	if (img.hasAttr("data-current-rotation"))
	{
		currentRotation = Number.parseInt(img.attr("data-current-rotation"))
	}

	currentRotation += rotationFactor;
	if (Math.abs(currentRotation) == 360)
	{
		currentRotation = 0;
	}

	var scale = 1;
	if (Math.abs(currentRotation) == 90 || Math.abs(currentRotation) == 270)
	{
		scale = img.get(0).naturalHeight / img.get(0).naturalWidth;
	}

	img.attr("data-current-rotation", currentRotation);
	img.css("transform", `rotate(${currentRotation}deg) scale(${scale})`);
}

$(document).on("click", ".rotate-image-button", function(e)
{
	e.preventDefault();

	var button = $(e.currentTarget);
	RotateImage(button.parent().parent().parent().find("img"), Number.parseInt(button.attr("data-rotation-factor")));
});

$(".video-inline").on("fullscreenchange", function(e)
{
	$(e.currentTarget).toggleClass("img-thumbnail");
});
