using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Mvc.Html;
using System.Web.Mvc.Ajax;
using System.Web.Helpers;
using System.Web.WebPages;
using System.Drawing;
using System.Drawing.Imaging;
using System.IO;


namespace SnapshotServer.Controllers
{
	public class HomeController : Controller
	{
		public ActionResult Index ()
		{
			ViewData ["Message"] = "Welcome to ASP.NET MVC on Mono!";
			return View ();
		}

		[HttpPost]
		public JsonResult Save ()
		{


			try {
				ValidateRequestHeader();

				if (!string.IsNullOrEmpty(Request["name"]) && !string.IsNullOrEmpty(Request["image"]) && !string.IsNullOrEmpty(Request["type"])) {
					//set the save path in a config
					string savedFileName = Server.MapPath(string.Format("~/Content/{0}.{1}",Request["name"],Request["type"]));
					SaveByteArrayAsImage(savedFileName, Request["image"], Request["type"]);
				} else {
					throw new Exception("Required values are missing");
				}
			} catch (Exception error) {
				return Error(error.Message);
			} 

			return Success("Successfully Saved");
		}

		protected JsonResult Error(string message) {
			return Json (new { error = message });
		}

		protected JsonResult Success(string message) {
			return Json (new { success = message });
		}

		protected string TokenHeaderValue()
		{
			string cookieToken, formToken;
			AntiForgery.GetTokens(null, out cookieToken, out formToken);
			return cookieToken + ":" + formToken;                
		}

		protected void ValidateRequestHeader()
		{
			string cookieToken = "";
			string formToken = "";

			if (!Request["token"].Equals(null))
			{
				string[] tokens = Request["token"].Split(':');
				if (tokens.Length == 2)
				{
					cookieToken = tokens[0].Trim();
					formToken = tokens[1].Trim();
				}
			}
			AntiForgery.Validate(cookieToken, formToken);
		}

		private void SaveByteArrayAsImage(string fullOutputPath, string base64String, string type)
		{
			byte[] bytes = Convert.FromBase64String(base64String);
			
			Image image;
			using (MemoryStream ms = new MemoryStream(bytes))
			{
				image = Image.FromStream(ms);

				ImageFormat imageFormat;

                switch (type) {
                    case "jpg":
                	    imageFormat = System.Drawing.Imaging.ImageFormat.Jpeg;
                	break;
                	case "png":
                	    imageFormat = System.Drawing.Imaging.ImageFormat.Png;
                	break;
                }

                image.Save(fullOutputPath, imageFormat);
			}
		}


		[HttpGet]
		public JsonResult Token ()
		{
			return Json (new { token = TokenHeaderValue() }, JsonRequestBehavior.AllowGet);
		}
	}
}

