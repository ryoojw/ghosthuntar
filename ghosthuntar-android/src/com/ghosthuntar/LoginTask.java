package com.ghosthuntar;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.EditText;

import com.ghosthuntar.account.UserFunctions;
import com.ghosthuntar.db.DatabaseHandler;

public class LoginTask extends AsyncTask<String, Void, Integer> {

	public static final String PREFS_USER_EMAIL = "email";
	
	private LoginView loginView;
	private ProgressDialog progressDialog;
	
	private int responseCode = 0;
	
	private static String KEY_SUCCESS = "success";
	private static String KEY_UID = "uid";
	private static String KEY_NAME = "name";
	private static String KEY_EMAIL = "email";
	private static String KEY_JOIN_DATE = "join_date";
	
	public LoginTask(LoginView loginView, ProgressDialog progressDialog) {
		this.loginView 		= loginView;
		this.progressDialog = progressDialog;
	}
	
	@Override
	protected Integer doInBackground(String... arg0) {
		EditText emailEdit		= (EditText) loginView.findViewById(R.id.loginEmail);
		EditText passwordEdit	= (EditText) loginView.findViewById(R.id.loginPassword);
		
		String email 	= emailEdit.getText().toString();
		String password = passwordEdit.getText().toString();
		
		UserFunctions userFunction 	= new UserFunctions();
		JSONObject json 			= userFunction.loginUser(email, password);
		
		// Check for login response
		try {
			if (json.getString(KEY_SUCCESS) != null) {
				String res = json.getString(KEY_SUCCESS);

				if(Integer.parseInt(res) == 1){
					//user successfully logged in
					// Store user details in SQLite Database
					DatabaseHandler db = new DatabaseHandler(loginView.getApplicationContext());
					JSONObject json_user = json.getJSONObject("user");
					
					// Clear all previous data in database
					userFunction.logoutUser(loginView.getApplicationContext());
					db.addUser(json_user.getString(KEY_NAME), json_user.getString(KEY_EMAIL), 
							json.getString(KEY_UID), json_user.getString(KEY_JOIN_DATE));                        

					responseCode = 1;

				}else{
					responseCode = 0;
					// Error in login
				}
			}
			
		} catch (NullPointerException e) {
			e.printStackTrace();
		} catch (JSONException e) {
			e.printStackTrace();
		}
		
		return responseCode;
	}
	
	@Override
	protected void onPreExecute() {
		progressDialog.show();
	}
	
	@Override
	protected void onPostExecute(Integer responseCode) {
		EditText userName 		= (EditText) loginView.findViewById(R.id.loginEmail);
		EditText passwordEdit 	= (EditText) loginView.findViewById(R.id.loginPassword);
		
		if (responseCode == 1) {
			progressDialog.dismiss();
			
			SaveSharedPreference.setUserName(loginView, userName.getText().toString());
			
			Intent i = new Intent();
			i.setClass(loginView.getApplicationContext(), MainActivity.class);
			loginView.startActivity(i);	
			loginView.finish();
		}
		else {
			progressDialog.dismiss();
			loginView.showLoginError(responseCode);
	
		}
	}
}
