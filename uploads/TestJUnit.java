import org.junit.*;
import static org.junit.Assert.*;

public class TestJUnit {

  @Test
  public void test() {
    assertTrue(1 == 1);
    System.out.println("Test 1 done");
  }

  @Test
  public void testTwo() {
    //assertFalse(1 == 1);
    assertTrue(1 == 1);
    System.out.println("Test 2 done");
  }
}
