package com.ghosthuntar.account;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import android.content.Context;
import android.util.Log;

import com.ghosthuntar.db.DatabaseHandler;
import com.ghosthuntar.util.JSONParser;

public class UserFunctions {
	
	private JSONParser jsonParser;
	
	private static String loginURL 		= "http://www.ghosthuntar.com/app";
	private static String login_tag 	= "login";
	
	private static String signupURL		= "http://www.ghosthuntar.com/app";
	private static String signup_tag 	= "signup";
	
	public UserFunctions() {
		jsonParser = new JSONParser();
	}
	
	public JSONObject loginUser(String email, String password) {
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("tag", login_tag));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("password", password));
		
		JSONObject json = jsonParser.getJSONFromUrl(loginURL, params);
		
		//Log.d("DEBUG", "json: " + json);
		
		return json;
	}
	
	//determine if the user is logged in
    public boolean isUserLoggedIn(Context context){
        DatabaseHandler db = new DatabaseHandler(context);
        int count = db.getRowCount();
        if(count > 0){
            // user logged in
            return true;
        }
        return false;
    }
 
    //logout the user
    public boolean logoutUser(Context context){
        DatabaseHandler db = new DatabaseHandler(context);
        db.resetTables();
        return true;
    }
    
    // Sign up a new user with name/email/password
    public JSONObject signupUser(String name, String email, String password) {
    	List<NameValuePair> params = new ArrayList<NameValuePair>();
    	params.add(new BasicNameValuePair("tag", signup_tag));
    	params.add(new BasicNameValuePair("name", name));
    	params.add(new BasicNameValuePair("email", email));
    	params.add(new BasicNameValuePair("password", password));
    	
    	// getting JSON object
    	JSONObject json = jsonParser.getJSONFromUrl(signupURL, params);
    	
    	//Log.d("DEBUG", "json: " + json);
    	
    	return json;
    }
}
