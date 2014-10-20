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

public class SignupView extends Activity {

	private Button btnRegister;
	private Button btnLinkToLogin;
	
	private EditText inputFullName;
	private EditText inputEmail;
	private EditText inputPassword;
	private EditText inputConfirmPassword;
	private TextView registerErrorMsg;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Remove title bar
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// Go full screen, remove notification bar
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
		
		setContentView(R.layout.signup_view);
		
		// Get all assets
		inputFullName 		= (EditText) findViewById(R.id.registerName);
        inputEmail 			= (EditText) findViewById(R.id.registerEmail);
        inputPassword 		= (EditText) findViewById(R.id.registerPassword);
        btnRegister 		= (Button) findViewById(R.id.btnRegister);
        btnLinkToLogin 		= (Button) findViewById(R.id.btnLinkToLoginScreen);
        registerErrorMsg 	= (TextView) findViewById(R.id.register_error);
        
        // Sign up button click event
        btnRegister.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				ProgressDialog progressDialog = new ProgressDialog(SignupView.this);
				progressDialog.setMessage("Signing Up...");
				
				SignupTask signupTask = new SignupTask(SignupView.this, progressDialog);
				signupTask.execute();
			}
		});
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.signup_view, menu);
		return true;
	}
	
	public void getLoginView(View view) {
		startActivity(new Intent(this, LoginView.class));
		finish();
	}
	
	public void onBackPressed() {
		startActivity(new Intent(this, StartView.class));
		finish();
	}
	
	public void signupReport(Integer responseCode) {
		int duration 	= Toast.LENGTH_LONG;
		Context context = getApplicationContext();
		
		if (responseCode == 0) {
			Toast toast = Toast.makeText(context, "Register Error", duration);
			toast.show();
		}
		else {
			Toast toast = Toast.makeText(context, "Register Success", duration);
			toast.show();
            Intent i = new Intent(getApplicationContext(), MainActivity.class);
            startActivity(i);
            finish();
		}
	}
}
