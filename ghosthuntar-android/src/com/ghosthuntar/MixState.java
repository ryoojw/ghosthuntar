/*
 * Copyright (C) 2010- Peer internet solutions
 * 
 * This file is part of mixare.
 * 
 * This program is free software: you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 3 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License 
 * for more details. 
 * 
 * You should have received a copy of the GNU General Public License along with 
 * this program. If not, see <http://www.gnu.org/licenses/>
 */
package com.ghosthuntar;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;
import org.mixare.lib.MixContextInterface;
import org.mixare.lib.MixStateInterface;
import org.mixare.lib.MixUtils;
import org.mixare.lib.render.Matrix;
import org.mixare.lib.render.MixVector;

import android.util.Log;

import com.ghosthuntar.util.JSONParser;

/**
 * This class calculates the bearing and pitch out of the angles
 */
public class MixState implements MixStateInterface{

	public static int NOT_STARTED = 0; 
	public static int PROCESSING = 1; 
	public static int READY = 2; 
	public static int DONE = 3; 

	int nextLStatus = MixState.NOT_STARTED;
	String downloadId;

	private float curBearing;
	private float curPitch;

	private boolean detailsView;

	public boolean handleEvent(MixContextInterface ctx, String onPress) {
		if (onPress != null && onPress.startsWith("webpage")) {
			try {
				String webpage = MixUtils.parseAction(onPress);
				
				//Log.i("Ghost HuntAR", "webpage: " + webpage);
				
				this.detailsView = true;
				
				// do something with the url link
				ghostCapture(webpage);
				
				//ctx.loadMixViewWebPage(webpage);
			} catch (Exception ex) {
				ex.printStackTrace();
			}
		} 
		return true;
	}

	/*public boolean handleEvent(MixContextInterface ctx, String onPress) {
		if (onPress != null && onPress.startsWith("webpage")) {
			try {
				String webpage = MixUtils.parseAction(onPress);
				this.detailsView = true;
				ctx.loadMixViewWebPage(webpage);
			} catch (Exception ex) {
				ex.printStackTrace();
			}
		} 
		return true;
	}*/

	public float getCurBearing() {
		return curBearing;
	}

	public float getCurPitch() {
		return curPitch;
	}
	
	public boolean isDetailsView() {
		return detailsView;
	}
	
	public void setDetailsView(boolean detailsView) {
		this.detailsView = detailsView;
	}

	public void calcPitchBearing(Matrix rotationM) {
		MixVector looking = new MixVector();
		rotationM.transpose();
		looking.set(1, 0, 0);
		looking.prod(rotationM);
		this.curBearing = (int) (MixUtils.getAngle(0, 0, looking.x, looking.z)  + 360 ) % 360 ;

		rotationM.transpose();
		looking.set(0, 1, 0);
		looking.prod(rotationM);
		this.curPitch = -MixUtils.getAngle(0, 0, looking.y, looking.z);
	}
	
	private void ghostCapture(String webpage) {
		
		// Take photo of ghost on touch
		ghostPhoto();
		
		// Make call to web server to change new ghost owner
		ghostCaptureUrl(webpage);
		
		Log.i("Ghost HuntAR", "Ghost Captured!");
	}
	
	private void ghostPhoto() {
		
	}
	
	private void ghostCaptureUrl(String webpage) {
		String web_vars[] = webpage.split("\\|", -1);
		String web_url 		= web_vars[0];
		String player_email = web_vars[1];
		String ghost_id 	= web_vars[2];
		
		Log.i("Ghost HuntAR", "web_url: " + web_url + ", player_email: " + player_email + ", ghost_id: " + ghost_id);
		
		JSONParser jsonParser = new JSONParser();
		
		String capture_tag = "capture";
		
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("tag", capture_tag));
		params.add(new BasicNameValuePair("player_email", player_email));
		params.add(new BasicNameValuePair("ghost_id", ghost_id));
		
		JSONObject json = jsonParser.getJSONFromUrl(web_url, params);
		
		Log.i("Ghost HuntAR", "json: " + json);
	}
}
