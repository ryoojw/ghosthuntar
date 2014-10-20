package com.ghosthuntar;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class LoginView extends Activity {
	
	private Button btnLogin;
	private Button btnLinkToSignup;
	private EditText inputEmail;
	private EditText inputPassword;
	private TextView loginErrorMsg;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Remove title bar
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// Go full screen, remove notification bar
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
		
		setContentView(R.layout.login_view);
		
		// Get all assets
		inputEmail 		= (EditText) findViewById(R.id.loginEmail);
		inputPassword 	= (EditText) findViewById(R.id.loginPassword);
		btnLogin 		= (Button) findViewById(R.id.btnLogin);
		btnLinkToSignup = (Button) findViewById(R.id.btnLinkToSignupScreen);
		loginErrorMsg	= (TextView) findViewById(R.id.login_error);
		
		// Login button Click Event
		btnLogin.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				ProgressDialog progressDialog = new ProgressDialog(LoginView.this);
				progressDialog.setMessage("Logging in...");
				
				LoginTask loginTask = new LoginTask(LoginView.this, progressDialog);
				loginTask.execute();
			}
		});
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.login_view, menu);
		return true;
	}
	
	public void getSignupView(View view) {
		startActivity(new Intent(this, SignupView.class));
		finish();
	}
	
	public void showLoginError(int responseCode) {
    	int duration = Toast.LENGTH_LONG;
    	Context context = getApplicationContext();
		Toast toast = Toast.makeText(context, "Login Error", duration);
		toast.show();
    }
	
	public void onBackPressed() {
		startActivity(new Intent(this, StartView.class));
		finish();
	}
}
