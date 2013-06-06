import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.assertTrue;

import org.junit.Test;
/**
 * A unit test for LinkedPriorityList with only one test to ensure
 * an exception is thrown with an attempt to remove form an empty list.
 * 
 * Recommended:  Copy and paste in your @Test methods from ArrayPriorityList
 * and change all occurrences of ArrayPriorityList to LinkedPriorityList.
 * 
 */
public class LinkedPriorityListTest {
	@Test
	public void testInsertToLeft() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		assertTrue(list.isEmpty());
		list.insertElementAt(0, "First");
		assertEquals("First", list.getElementAt(0));
		assertEquals(1, list.size());
		assertFalse(list.isEmpty());
		// Must shift array elements in this case
		list.insertElementAt(0, "New First");
		assertEquals("New First", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
		list.insertElementAt(1, "New Second");
		assertEquals("New First", list.getElementAt(0));
		assertEquals("New Second", list.getElementAt(1));
		assertEquals("First", list.getElementAt(2));
		assertEquals(3, list.size());
	}
	@Test
	public void testInsertAtEnd() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		assertEquals("First", list.getElementAt(0));
		list.insertElementAt(1, "Last");
		assertEquals("Last", list.getElementAt(1));
		list.insertElementAt(2, "New Last");
		assertEquals("New Last", list.getElementAt(2));
	}
	@Test
	public void testToArray() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		Object[] result = list.toArray();
		assertEquals("New First", result[0]);
		assertEquals("New Second", result[1]);
		assertEquals("First", result[2]);
	}

	@Test
	public void testRemove() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.removeElementAt(1);
		assertEquals(2, list.size());
		assertEquals("New First", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
	}
	@Test
	public void testRemoveAtFront() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.removeElementAt(0);
		assertEquals(2, list.size());
		assertEquals("New Second", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
	}
	@Test
	public void testRemoveAtEnd() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.removeElementAt(2);
		assertEquals(2, list.size());
		assertEquals("New First", list.getElementAt(0));
		assertEquals("New Second", list.getElementAt(1));
	}
	@Test(expected = IllegalArgumentException.class)
	public void testRemoveLessThanZero() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.removeElementAt(-2);
		// Write short test methods to ensure methods throw exceptions
	}

	@Test(expected = IllegalArgumentException.class)
	public void testInsertLessThanZero() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(-2, "Hi");
	}

	// when they are supposed to throw new IllegalArgumentException();
	@Test(expected = IllegalArgumentException.class)
	public void testExceptionGetElementAtZeroWhenSizeIsZero() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.getElementAt(0);
	}

	@Test
	public void testLowerPriorityOf() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.lowerPriorityOf(1);
		assertEquals("New First", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
		assertEquals("New Second", list.getElementAt(2));
		list.lowerPriorityOf(2);
		assertEquals("New First", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
		assertEquals("New Second", list.getElementAt(2));
	}

	@Test(expected = IllegalArgumentException.class)
	public void testLowerPriorityException() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.lowerPriorityOf(-1);
	}

	@Test
	public void testRaisePriorityOf() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.raisePriorityOf(1);
		assertEquals("New Second", list.getElementAt(0));
		assertEquals("New First", list.getElementAt(1));
		assertEquals("First", list.getElementAt(2));
		list.raisePriorityOf(0);
		assertEquals("New Second", list.getElementAt(0));
		assertEquals("New First", list.getElementAt(1));
		assertEquals("First", list.getElementAt(2));
	}

	@Test(expected = IllegalArgumentException.class)
	public void testRaisePriorityException() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.raisePriorityOf(-1);
	}

	@Test
	public void testMoveToLast() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.moveToLast(0);
		assertEquals("New Second", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
		assertEquals("New First", list.getElementAt(2));
		list.moveToLast(1);
		assertEquals("New Second", list.getElementAt(0));
		assertEquals("New First", list.getElementAt(1));
		assertEquals("First", list.getElementAt(2));
		list.moveToLast(2);
	}

	@Test(expected = IllegalArgumentException.class)
	public void testMoveToLastException() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.moveToLast(-1);
	}

	@Test
	public void testMoveToFirst() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.insertElementAt(0, "First");
		list.insertElementAt(0, "New First");
		list.insertElementAt(1, "New Second");
		list.moveToTop(2);
		assertEquals("First", list.getElementAt(0));
		assertEquals("New First", list.getElementAt(1));
		assertEquals("New Second", list.getElementAt(2));
		list.moveToTop(0);
		list.moveToTop(1);
		assertEquals("New First", list.getElementAt(0));
		assertEquals("First", list.getElementAt(1));
		assertEquals("New Second", list.getElementAt(2));
	}

	@Test(expected = IllegalArgumentException.class)
	public void testMoveToTopException() {
		LinkedPriorityList<String> list = new LinkedPriorityList<String>();
		list.moveToTop(-1);
	}
	@Test
	public void testMovie() {
	// Arguments: 1. Movie Title 2. Star Rating from 1..5
	Movie m1 = new Movie("The Matrix Revolutions", 4);
	Movie m2 = new Movie("The Lord of the Rings, Return of the King", 5);
	Movie m3 = new Movie("Click", 2);
	assertEquals("The Matrix Revolutions", m1.getTitle());
	assertEquals("The Lord of the Rings, Return of the King", m2.getTitle());
	assertEquals("Click", m3.getTitle());
	assertEquals("The Matrix Revolutions ****", m1.toString());
	assertEquals("The Lord of the Rings, Return of the King *****", m2.toString());
	assertEquals("Click **", m3.toString());
}


  // You may reuse your previous test methods as long it is your own code.  
  
  
  // This test will pass only if removeElementAt throws an IllegalArgumentException
  @Test(expected = IllegalArgumentException.class)
  public void testFailedRemoveShouldThrowExceptionWhenIndexIsZeroAndTheListIsEmpty() {
    PriorityList<String> list = new LinkedPriorityList<String>();
    list.removeElementAt(0);
  }
  
}