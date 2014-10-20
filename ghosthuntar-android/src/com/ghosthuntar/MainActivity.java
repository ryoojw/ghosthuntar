package com.ghosthuntar;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;

public class MainActivity extends Activity {
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Remove title bar
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		
		// Go full screen, remove notification bar
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
		
		setContentView(R.layout.main);
	}
	
	public void play(View view) {
		startActivity(new Intent(this, GameView.class));
	}
	
	public void logout(View view) {
		SaveSharedPreference.setUserName(this, "");
		
		startActivity(new Intent(this, StartView.class));
		finish();
	}
}