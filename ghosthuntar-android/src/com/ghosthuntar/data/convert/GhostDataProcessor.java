package com.ghosthuntar.data.convert;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.mixare.lib.HtmlUnescape;
import org.mixare.lib.marker.Marker;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.util.Log;

import com.ghosthuntar.data.DataHandler;
import com.ghosthuntar.data.DataSource;
import com.ghosthuntar.lib.ghostmarker.GhostMarker;

public class GhostDataProcessor extends DataHandler implements DataProcessor {
	
	public static final int MAX_JSON_OBJECTS = 1000;
	
	private static final String[] URL_MATCH = {"ghost"};
	private static final String[] DATA_MATCH = {"ghost"};
	
	@Override
	public String[] getUrlMatch() {
		return URL_MATCH;
	}

	@Override
	public String[] getDataMatch() {
		return DATA_MATCH;
	}

	@Override
	public boolean matchesRequiredType(String type) {
		Log.d("Ghost HuntAR", "matechesRequiredType type: " + type + ", DataSource.TYPE.GHOSTHUNTAR.name(): " + DataSource.TYPE.GHOSTHUNTAR.name());
		
		if (type.equals(DataSource.TYPE.GHOSTHUNTAR.name())) {
			return true;
		}
		
		return false;
	}

	@Override
	public List<Marker> load(String rawData, int taskId, int colour) throws JSONException {
		List<Marker> 	markers 	= new ArrayList<Marker>();
		JSONObject 		root 		= convertToJSON(rawData);
		JSONArray 		dataArray	= root.getJSONArray("results");
		int				top			= Math.min(MAX_JSON_OBJECTS, dataArray.length());
		
		for (int i = 0; i < top; i++) {
			JSONObject jo = dataArray.getJSONObject(i);
			
			if (jo.has("name") && jo.has("latitude") && jo.has("longitude") && jo.has("elevation")) {
				String link = null;
				
				Bitmap ghost_image 		= getBitmapFromURL(jo.getString("ghost_image_url"));
				Bitmap capture_image 	= getBitmapFromURL(jo.getString("capture_image_url"));
				
				// Image Marker
				Marker ma = new GhostMarker(
						String.valueOf(jo.getInt("id")),
						HtmlUnescape.unescapeHTML(jo.getString("name"), 0),
						jo.getDouble("latitude"),
						jo.getDouble("longitude"),
						jo.getDouble("elevation"),
						taskId,
						colour,
						ghost_image,
						capture_image);
				
				markers.add(ma);
			}
		}
		
		return markers;
	}
	
	private JSONObject convertToJSON(String rawData){
		try {
			return new JSONObject(rawData);
		} catch (JSONException e) {
			throw new RuntimeException(e);
		}
	}
	
	public Bitmap getBitmapFromURL(String src) {
		if(src.startsWith("http://")){
			return getBitmapFromWebURL(src);
		}else if(src.startsWith("file://")){
			return getBitmapFromFile(src);
		}else{
			Log.e("Ghost HuntAR", "getbitmapfromurl throwed an unsupported url: "+ src);
			return null;
		}
	}
	
	private Bitmap getBitmapFromFile(String src){
		try {
			InputStream input = new FileInputStream(new File(src.replace("file://", "")));
			return BitmapFactory.decodeStream(input);			
		} catch (IOException e) {
			Log.e("Ghost HuntAR", "io exception, when getting the bitmap from the file: "+ src);
			return null;
		}		
	}
	
	private Bitmap getBitmapFromWebURL(String src){
		try {
			URLConnection urlConnection = null;
			URL url = new URL(src);
			urlConnection = url.openConnection();
			urlConnection.setDoInput(true);
			urlConnection.connect();
			InputStream input = urlConnection.getInputStream();
			return BitmapFactory.decodeStream(input);
			
		} catch (IOException e) {
			Log.e("Ghost HuntAR", "io exception, when getting the bitmap from the url: "+ src);
			return null;
		}		
	}
}
