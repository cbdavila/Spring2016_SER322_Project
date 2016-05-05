package databaseGeneration;

import java.io.File;
import java.io.PrintWriter;
import java.util.Random;
import java.util.Scanner;

public class FollowingGenerator {

	String languageName = "";
	String languageID="";
	String dbName = "";
	String query = "";
	String outputFile = "followingData.txt";
	int counter = 0;
	String stPt1 = "INSERT INTO `";
	String stPt2 = "`.`following` (`Follower`, `Followee`) VALUES (‘";
	String stPt3 = "','";
	String stPt4 = "’);";
	
	String [] users = {"BoatsAndHoes11",
			"CarlosD",
			"ChrisC",
			"robertB",
			"RobinSmith",
			"shellysmith",
			"Tompp",
			"yoda1",
			"abc123",
			"josealva",
			"mariaduk",
			"monkeyMan",
			"Whiskey56",
			"aviyosef",
			"pimplePopper",
			"roys",
			"saralevi",
			"pierlesu",
			"sushiWoman",
			"TylerD",
			"WaxOnWaxOff",
			"malikkabul",
			"taliktamir",
			"buckle1",
			"mickeyYolo1",
			"itsbitzy",
			"mickeyYolo",
			"eskimo100",
			"watupdude",
			"i_am_Yoda",
			"imtheCaptainNow",
			"monkeySee",
			"MonkeyDo",
			"MisterTal",
			"windowCleaner",
			"IbanRuss",
			"Charly15",
			"JJdidtie",
			"mcusc33",
			"passwd123123",
			"smartWaterMan",
			"GardenGuy",
			"CashMoney",
			"DMme",
			"ObiOneKonobi",
			"jetman44",
			"jetman",
			"housten177",
			"mrSaki",
			"sushiMan",
			"expoMarker",
			"appleEater",
			"smartExpo",
			"ImmigrationMan",
			"realDonaldTrumpsExWife",
			"Obama1775",
			"Kruschev11",
			"Hood11",
			"MyAnimalIs",
			"UglyMonkey",
			"loveMoney",
			"loveTraveling",
			"realPresident",
			"TravelingMan44",
			"TravelerPhoto",
			"JensPhoto",
			"GraphIt33",
			"Embedded3",
			"WebDeveloper3",
			"WebDev1",
			"WebDev4",
			"WebDev2",
			"footDoc",
			"marriamCandy",
			"websterDictionary"};
	
	public FollowingGenerator(){
		
	}
	
	public void generateScript(){
		Scanner scanner;
		try {
            Scanner input = new Scanner(System.in);		//reads data
			
            System.out.println("Enter your database name");
            dbName = input.nextLine();
            System.out.println("Your entered entered : " +dbName);

            PrintWriter writer = new PrintWriter(outputFile, "UTF-8");
            for(int i = 0; i < users.length; i++){
            	Random rand = new Random();
            	int  numToFollow = rand.nextInt(15) + 5;
            	for(int j = 0; j < numToFollow; j++){
            		int modInx = (i + j) % users.length; 
            		writer.println(stPt1+dbName+stPt2+users[i]+stPt3+users[modInx]+stPt4);
            	}	
            }
            writer.close();
            input.close();

        } catch (Exception ex) {
            System.out.println("Exception was caught");
        }
		
	}
	
	public static void main(String args[]){
		FollowingGenerator test =new FollowingGenerator();
		test.generateScript();
		
	}
}
