import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.util.Scanner;

public class CountryScan {
	String countryName = "";
	String dbName = "";
	String population = "";
	String countryID = "";
	String query = "";
	String filename = "countryData.txt";
	
	public CountryScan(){
		
	}
	
	public void getFileContents(){
		Scanner scanner;
		try {
            Scanner input = new Scanner(System.in);		//reads data
           // FileWriter fw = new FileWriter(outputFile);
			//BufferedWriter bw = new BufferedWriter(fw);	//write output
			
            System.out.println("Enter your database name");
            dbName = input.nextLine();
            
            System.out.println("You entered : " +dbName);
            
            File file = new File(filename);
            System.out.println("Reading From ... " +filename);
            //System.out.println("Writing to ..." +outputFile);

            input = new Scanner(file);
            while(input.hasNextLine()){
            	query=input.nextLine();
            	query=query.replace("projectdb", dbName);
            	System.out.println(query);
            }
            
           /* while (input.hasNextLine()) {
            	this.countryID = input.next();
            	this.countryName = input.next();
            	this.population = input.next();
            	query = ("INSERT INTO `"+dbName+"`.`Country` (`CountryId`, `CountryName`, `Population`) VALUES ('"+countryID+"', '"+countryName+"', '"+population+"');");
            	System.out.println(query);
            }
            bw.close();
            */
            input.close();

        } catch (Exception ex) {
            System.out.println("End of File");
        }
		
	}
	
	public static void main(String args[]){
		CountryScan test =new CountryScan();
		test.getFileContents();
		
	}
}
