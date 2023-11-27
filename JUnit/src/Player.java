import java.time.Year;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class Player {
    private String name;
    private Integer bornYear;
    private boolean agree;

    public boolean createPlayer(boolean agree, String name, Integer born){
        setName(name);
        if(setBornYear(born)){
            return agree;
        }
        return false;
    }

    public void setName(String name) {
        String rgx = "^[a-zA-Z0-9_-]*$";
        Pattern p = Pattern.compile(rgx);
        Matcher m = p.matcher(name);

        if(m.matches() && name !=""){
            this.name = name;
        } else this.name = null;
    }

    public boolean setBornYear(Integer born) {
        int currYear = Year.now().getValue();
        if(currYear - born >= 18){
            this.bornYear = born;
            return true;
        }
        return false;
    }

    public String getName(){
        return this.name;
    }

}
