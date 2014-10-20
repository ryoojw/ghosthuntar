package com.ghosthuntar;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.widget.EditText;

import com.ghosthuntar.account.UserFunctions;
import com.ghosthuntar.db.DatabaseHandler;

public class SignupTask extends AsyncTask<String, Void, Integer> {
	
	private SignupView signupView;
	private ProgressDialog progressDialog;
	
	private static String KEY_SUCCESS 	= "success";
	private static String KEY_UID 		= "uid";
	private static String KEY_NAME 		= "name";
	private static String KEY_EMAIL		= "email";
	private static String KEY_JOIN_DATE	= "join_date";
	
	private int responseCode = 0;
	
	public SignupTask(SignupView signupView, ProgressDialog progressDialog) {
		this.signupView 	= signupView;
		this.progressDialog = progressDialog;
	}

	@Override
	protected Integer doInBackground(String... params) {
		EditText nameEdit				= (EditText) signupView.findViewById(R.id.registerName);
		EditText emailEdit 				= (EditText) signupView.findViewById(R.id.registerEmail);
		EditText passwordEdit 			= (EditText) signupView.findViewById(R.id.registerPassword);
		EditText confirmPasswordEdit 	= (EditText) signupView.findViewById(R.id.registerConfirmPassword);
		
		String name 			= nameEdit.getText().toString();
		String email 			= emailEdit.getText().toString();
		String password 		= passwordEdit.getText().toString();
		String confirmPassword 	= confirmPasswordEdit.getText().toString();
		
		UserFunctions userFunction = new UserFunctions();
		JSONObject json = userFunction.signupUser(name, email, password);
		
		// Check for login response
		try {
			if (json.getString(KEY_SUCCESS) != null) {
				String res = json.getString(KEY_SUCCESS);
				
				if (Integer.parseInt(res) == 1) {
					DatabaseHandler db = new DatabaseHandler(signupView.getApplicationContext());
					JSONObject json_user = json.getJSONObject("user");
					
					// Clear all previous data in database
					userFunction.logoutUser(signupView.getApplicationContext());
					db.addUser(json_user.getString(KEY_NAME),
							json_user.getString(KEY_EMAIL),
							json.getString(KEY_UID),
							json_user.getString(KEY_JOIN_DATE));
					
					responseCode = 1;
					
				} else {
					responseCode = 0;
				}
			}
			
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
		EditText nameEdit				= (EditText) signupView.findViewById(R.id.registerName);
		EditText emailEdit 				= (EditText) signupView.findViewById(R.id.registerEmail);
		EditText passwordEdit 			= (EditText) signupView.findViewById(R.id.registerPassword);
		EditText confirmPasswordEdit 	= (EditText) signupView.findViewById(R.id.registerConfirmPassword);
		
		String name 			= nameEdit.getText().toString();
		String email 			= emailEdit.getText().toString();
		String password 		= passwordEdit.getText().toString();
		String confirmPassword 	= confirmPasswordEdit.getText().toString();
		
		if (responseCode == 1) {
			progressDialog.dismiss();
			signupView.signupReport(responseCode);
			
			nameEdit.setText("");
			passwordEdit.setText("");
			confirmPasswordEdit.setText("");
		}
		
		if (responseCode == 0) {
			progressDialog.dismiss();
			signupView.signupReport(responseCode);
		}
	}
}
