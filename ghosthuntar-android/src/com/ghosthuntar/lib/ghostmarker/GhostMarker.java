package com.ghosthuntar.lib.ghostmarker;

import java.net.URLDecoder;
import java.text.DecimalFormat;

import org.mixare.lib.MixContextInterface;
import org.mixare.lib.MixStateInterface;
import org.mixare.lib.MixUtils;
import org.mixare.lib.gui.Label;
import org.mixare.lib.gui.PaintScreen;
import org.mixare.lib.gui.ScreenLine;
import org.mixare.lib.gui.TextObj;
import org.mixare.lib.marker.Marker;
import org.mixare.lib.marker.draw.ParcelableProperty;
import org.mixare.lib.marker.draw.PrimitiveProperty;
import org.mixare.lib.reality.PhysicalPlace;
import org.mixare.lib.render.Camera;
import org.mixare.lib.render.MixVector;

import android.graphics.Bitmap;
import android.graphics.Color;
import android.location.Location;

import com.ghosthuntar.GameView;
import com.ghosthuntar.SaveSharedPreference;

public class GhostMarker implements Marker {
	
	public static final int MAX_OBJECTS = 100;
	
	public static int count = 0;
	
	public MixVector cMarker = new MixVector();
	public Label txtLab = new Label();
	
	protected MixVector signMarker = new MixVector();
	protected MixVector locationVector 	= new MixVector();
	protected String name;
	protected PhysicalPlace mGeoLoc;
	protected TextObj textBlock;
	protected boolean isVisible;
	protected boolean underline = false;
	protected double distance;
	
	private MixVector origin 	= new MixVector(0, 0, 0);
	private MixVector upV		= new MixVector(0, 1, 0);
	private ScreenLine pPt 		= new ScreenLine();
	
	private Bitmap ghost_image;
	private Bitmap capture_image;
	
	private String ID;
	private String URL;
	private boolean active;
	private int colour;
	
	public GhostMarker(String id, String name, double latitude, double longitude, double altitude, int type, int colour, Bitmap ghost_image, Bitmap capture_image) {
		this.active 		= false;
		this.name 			= name;
		this.mGeoLoc 		= new PhysicalPlace(latitude, longitude, altitude);
		this.colour			= colour;
		this.ID				= id + "##" + type + "##" + name;
		this.ghost_image	= ghost_image;
		this.capture_image	= capture_image;
		this.URL			= "webpage:" + URLDecoder.decode("http://ghosthuntar.com/app/changeOwner");
		this.underline 		= true;
		
		// Add the player data and ghost data to the URL because
		// we can't change the Mixare library.
		
		String username = SaveSharedPreference.getUserName(GameView.dataView.getContext());
		this.URL += "|" + username + "|" + id;
	}
	
	public double getLatitude() { return mGeoLoc.getLatitude(); }
	public double getLongitude() { return mGeoLoc.getLongitude(); }
	public double getAltitude() { return mGeoLoc.getAltitude(); }
	
	public double getDistance() { return distance; }
	public void setDistance(double distance) { this.distance = distance; }
	
	public String getID() { return ID; }
	public void setID(String iD) { ID = iD; }
	
	public MixVector getLocationVector() { return locationVector; }
	
	public void update(Location curGPSFix) {
		if (mGeoLoc.getAltitude() == 0.0)
			mGeoLoc.setAltitude(curGPSFix.getAltitude());
		
		// Compute the relative position vector from user position to POI location
		PhysicalPlace.convLocToVec(curGPSFix, mGeoLoc, locationVector);
	}
	
	public void calcPaint(Camera viewCam, float addX, float addY) {
		cCMarker(origin, viewCam, addX, addY);
		calcV(viewCam);
	}
	
	public void draw(PaintScreen dw) {
		drawImage(dw);
		drawTextBlock(dw);
	}
	
	/**
	 * Update this code to percentages for opacity and size
	 * in relation to distance.
	 * 
	 * @param dw
	 */
	public void drawImage(PaintScreen dw) {
		
		//Bitmap paint_image = image;
		
		if (isVisible) {
			
			/*if (distance < 100.0) {
				dw.setColor(Color.argb(255, 255, 255, 255));
			} else */
			
			if (distance < 500) {
				dw.setColor(Color.argb(255, 255, 255, 255));
				ghost_image = capture_image;
			} else if (distance < 1000) {
				dw.setColor(Color.argb(180, 255, 255, 255));
				ghost_image = resizeBitmap(ghost_image, 160, 151);
			} else {
				dw.setColor(Color.argb(155, 255, 255, 255));
				ghost_image = resizeBitmap(ghost_image, 120, 113);
			}
			
			dw.paintBitmap(ghost_image, signMarker.x - (ghost_image.getWidth() / 2), signMarker.y - (ghost_image.getHeight() / 2));
		}
	}
	
	public void drawCircle(PaintScreen dw) {
		if (isVisible) {
			float maxHeight = dw.getHeight();
			dw.setStrokeWidth(maxHeight / 100f);
			dw.setFill(false);
			
			//dw.setColor(getColour()); // sets circle colour to green
			
			// draw circle with radius depending on distance
			// 0.44 is approx. vertical fov in radians
			double angle = 2.0 * Math.atan2(10, distance);
			double radius = Math.max(Math.min(angle / 0.44 * maxHeight, maxHeight), maxHeight / 25f);
			
			dw.paintCircle(cMarker.x, cMarker.y, (float) radius);
		}
	}
	
	public void drawTextBlock(PaintScreen dw) {
		float maxHeight = Math.round(dw.getHeight() / 10f) + 1;
		
		String textStr = "";
		
		double d = distance;
		
		DecimalFormat df = new DecimalFormat("@#");
		
		if (d < 1000.0)
			textStr = name + " (" + df.format(d) + "m)";
		else {
			d = d / 1000.0;
			textStr = name + " (" + df.format(d) + "km";
		}
		
		textBlock = new TextObj(textStr, Math.round(maxHeight / 2f) + 1, 250, dw, underline);
		
		if (isVisible) {
			float currentAngle = MixUtils.getAngle(cMarker.x, cMarker.y, signMarker.x, signMarker.y);
			
			txtLab.prepare(textBlock);
			
			dw.setStrokeWidth(1f);
			dw.setFill(true);
			dw.paintObj(txtLab, signMarker.x - txtLab.getWidth()
					/ 2, signMarker.y + maxHeight, currentAngle + 90, 1);
		}
	}
	
	public boolean fClick(float x, float y, MixContextInterface ctx, MixStateInterface state) {
		boolean evtHandled = false;

		if (isClickValid(x, y)) {
			evtHandled = state.handleEvent(ctx, URL);
		}
		return evtHandled;
	}
	
	private void cCMarker(MixVector originalPoint, Camera viewCam, float addX, float addY) {
		// Temp properties
		MixVector tmpa = new MixVector(originalPoint);
		MixVector tmpc = new MixVector(upV);
		
		tmpa.add(locationVector); // 3
		tmpc.add(locationVector); // 3
		
		tmpa.sub(viewCam.lco); // 4
		tmpc.sub(viewCam.lco); // 4
		
		tmpa.prod(viewCam.transform); // 5
		tmpc.prod(viewCam.transform); // 5
		
		MixVector tmpb = new MixVector();
		viewCam.projectPoint(tmpa, tmpb, addX, addY); // 6
		cMarker.set(tmpb); // 7
		viewCam.projectPoint(tmpc, tmpb, addX, addY); // 6
		signMarker.set(tmpb); // 7
	}
	
	private void calcV(Camera viewCam) {
		isVisible = false;
		
		if (cMarker.z < -1f)
			isVisible = true;
	}
	
	private boolean isClickValid(float x, float y) {
		// If the marker is not active (i.e. not shown in AR view)
		// we don't have to check for clicks
		if (!isActive() && !this.isVisible)
			return false;
		
		float currentAngle = MixUtils.getAngle(cMarker.x, cMarker.y, signMarker.x, signMarker.y);
		
		pPt.x = x - signMarker.x;
		pPt.y = y - signMarker.y;
		pPt.rotate((float) Math.toRadians(-(currentAngle + 90)));
		pPt.x += txtLab.getX();
		pPt.y += txtLab.getY();

		float objX = txtLab.getX() - txtLab.getWidth() / 2;
		float objY = txtLab.getY() - txtLab.getHeight() / 2;
		float objW = txtLab.getWidth();
		float objH = txtLab.getHeight();

		if (pPt.x > objX && pPt.x < objX + objW && pPt.y > objY
				&& pPt.y < objY + objH) {
			return true;
		} else
			return false;
	}

	@Override
	public int compareTo(Marker another) {
		Marker leftPm = this;
		Marker rightPm = another;
		
		return Double.compare(leftPm.getDistance(), rightPm.getDistance());
	}

	@Override
	public String getTitle() { return name; }

	@Override
	public String getURL() { return URL; }

	@Override
	public boolean isActive() { return active; }
	@Override
	public void setActive(boolean active) {
		this.active = active;
	}

	@Override
	public int getColour() { return colour; }

	@Override
	public Label getTxtLab() { return txtLab; }
	@Override
	public void setTxtLab(Label txtLab) {
		this.txtLab = txtLab;
	}

	@Override
	public int getMaxObjects() {
		return MAX_OBJECTS;
	}

	@Override
	public void setExtras(String name, ParcelableProperty parcelableProperty) {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void setExtras(String name, PrimitiveProperty primitiveProperty) {
		// TODO Auto-generated method stub
		
	}
	
	public Bitmap getGhostImage() { return ghost_image; }
	public void setGhostImage(Bitmap ghost_image) {
		this.ghost_image = ghost_image;
	}
	
	private Bitmap resizeBitmap(Bitmap originalBitmap, int newWidth, int newHeight) {
		Bitmap resizedBitmap = Bitmap.createScaledBitmap(originalBitmap, newWidth, newHeight, false);
		
		return resizedBitmap;
	}
}
