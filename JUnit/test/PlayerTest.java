import org.junit.After;
import org.junit.Before;
import org.junit.Test;

import static org.junit.Assert.*;

public class PlayerTest {

    Player p;
    @Before
    public void setUp() throws Exception {
        p = new Player();
    }

    @After
    public void tearDown() throws Exception {
        p = null;
    }

    @Test
    public void createPlayer() {
        boolean out = p.createPlayer(true, "imie", 2000);
        assertTrue("Gracz nie został utworzony", out);
    }

    @Test
    public void setName() {
        p.setName("soc");
        String out = p.getName();
        assertNotNull("Podana nazwa zawiera zakazane znaki", out);
    }

    @Test
    public void setBornYear() {
        boolean out = p.setBornYear(2000);
        assertTrue("Wiek niezgodny z wymaganiami", out);
    }
}