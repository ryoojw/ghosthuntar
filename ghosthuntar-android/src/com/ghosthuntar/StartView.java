package com.ghosthuntar;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;

public class StartView extends Activity {
	
	public static final String PREFS_USER_EMAIL = "email";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		if (SaveSharedPreference.getUserName(this).length() != 0) {
			startActivity(new Intent(this, MainActivity.class));
			finish();
		}
		
		// Remove title bar
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// Go full screen, remove notification bar
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
		
		setContentView(R.layout.start_view);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.start_view, menu);
		return true;
	}
	
	public void loginView(View view) {
		startActivity(new Intent(this, LoginView.class));
		finish();
	}
	
	public void signupView(View view) {
		startActivity(new Intent(this, SignupView.class));
		finish();
	}
}
