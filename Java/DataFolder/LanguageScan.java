import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.util.Scanner;

public class LanguageScan {
	String languageName = "";
	String languageID="";
	String dbName = "";
	String query = "";
	String filename = "languages.txt";
	String outputFile = "languagesData.txt";
	int counter = 0;
	public LanguageScan(){
		
	}
	
	public void getFileContents(){
		Scanner scanner;
		try {
            Scanner input = new Scanner(System.in);		//reads data
           // FileWriter fw = new FileWriter(outputFile);
			
            System.out.println("Enter your database name");
            dbName = input.nextLine();
            
            System.out.println("You entered : " +dbName);
            
            File file = new File(outputFile);
           // System.out.println("Reading From ... " +filename);

            input = new Scanner(file);
           while(input.hasNextLine()){
            	query=input.nextLine();
            	query=query.replace("projectdb", dbName);
            	System.out.println(query);
            }
        
            /*
            while (input.hasNextLine()) {
            	this.languageID = input.next();
            	this.languageName = input.next();
            	query = ("INSERT INTO `"+dbName+"`.`Language` (`LngID`, `LngName`) VALUES ('"+languageID+"', '"+languageName+"');");
            	
            	System.out.println(query);
            }
            */
            
            input.close();

        } catch (Exception ex) {
            System.out.println("\nEnd of File");
        }
		
	}
	
	public static void main(String args[]){
		LanguageScan test =new LanguageScan();
		test.getFileContents();
		
	}
}
